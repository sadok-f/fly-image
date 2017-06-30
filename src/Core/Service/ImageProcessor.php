<?php

namespace Core\Service;

use Core\Entity\Image;
use Core\Exception\AppException;
use League\Flysystem\Filesystem;

/**
 * Class ImageProcessor
 * @package Core\Service
 */
class ImageProcessor
{
    /** Bin path */
    const MOZJPEG_COMMAND = '/opt/mozjpeg/bin/cjpeg';
    const IM_CONVERT_COMMAND = '/usr/bin/convert';
    const IM_MOGRIFY_COMMAND = '/usr/bin/mogrify';
    const IM_IDENTITY_COMMAND = '/usr/bin/identify';
    const FACEDETECT_COMMAND = '/usr/local/bin/facedetect';
    const CWEBP_COMMAND = '/usr/bin/cwebp';

    /** Image options excluded from IM command */
    const EXCLUDED_IM_OPTIONS = ['quality', 'mozjpeg', 'refresh', 'webp-lossless'];

    /** @var Filesystem */
    protected $filesystem;

    /** @var array */
    protected $params;

    /** @var  Image */
    protected $image;

    /**
     * ImageProcessor constructor.
     *
     * @param Filesystem $filesystem
     */
    public function __construct(Filesystem $filesystem)
    {
        $this->filesystem = $filesystem;
    }

    /**
     * Save new FileName based on source file and list of options
     *
     * @param Image $image
     *
     * @return Image
     * @throws \Exception
     */
    public function processNewImage(Image $image): Image
    {
        $faceCrop         = $image->extract('face-crop');
        $faceCropPosition = $image->extract('face-crop-position');
        $faceBlur         = $image->extract('face-blur');
        $extract          = $image->extract('extract');
        $topLeftX     = $image->extract('extract-top-x');
        $topLeftY     = $image->extract('extract-top-y');
        $bottomRightX = $image->extract('extract-bottom-x');
        $bottomRightY = $image->extract('extract-bottom-y');

        $this->generateCmdString($image);

        if ($faceBlur && !$image->isGifSupport()) {
            $this->processBlurringFaces($image);
        }

        if ($faceCrop && !$image->isGifSupport()) {
            $this->processCroppingFaces($image, $faceCropPosition);
        }

        if ($extract) {
            $this->processExtraction($image, $topLeftX, $topLeftY, $bottomRightX, $bottomRightY);
        }

        $this->execute($image->getCommandString());

        if ($this->filesystem->has($image->getNewFileName())) {
            $this->filesystem->delete($image->getNewFileName());
        }

        $this->filesystem->write($image->getNewFileName(), stream_get_contents(fopen($image->getNewFilePath(), 'r')));

        return $image;
    }

    /**
     * Face detection cropping
     *
     * @param Image $image
     * @param int   $faceCropPosition
     */
    protected function processCroppingFaces(Image $image, int $faceCropPosition = 0)
    {
        if (!is_executable(self::FACEDETECT_COMMAND)) {
            return;
        }
        $commandStr = self::FACEDETECT_COMMAND." ".$image->getOriginalFile();
        $output = $this->execute($commandStr);
        if (empty($output[$faceCropPosition])) {
            return;
        }
        $geometry = explode(" ", $output[$faceCropPosition]);
        if (count($geometry) == 4) {
            list($geometryX, $geometryY, $geometryW, $geometryH) = $geometry;
            $cropCmdStr =
                self::IM_CONVERT_COMMAND.
                " '{$image->getOriginalFile()}' -crop {$geometryW}x{$geometryH}+{$geometryX}+{$geometryY} ".
                $image->getOriginalFile();
            $this->execute($cropCmdStr);
        }
    }

    /**
     * Extracts a region from an image
     *
     * @param Image $image
     * @param int   $topLeftX
     * @param int   $topLeftY
     * @param int   $bottomRightX
     * @param int   $bottomRightY
     */
    protected function processExtraction(Image $image, $topLeftX, $topLeftY, $bottomRightX, $bottomRightY)
    {
        $geometryW = $bottomRightX - $topLeftX;
        $geometryH = $bottomRightY - $topLeftY;

        $cropCmdStr
            = self::IM_CONVERT_COMMAND.
              " '{$image->getTemporaryFile()}' -crop '{$geometryW}'x'{$geometryH}'+'{$topLeftX}'+'{$topLeftY}' ".
              $image->getTemporaryFile();

        $this->execute($cropCmdStr);
    }

    /**
     * Blurring Faces
     *
     * @param Image $image
     */
    protected function processBlurringFaces(Image $image)
    {
        if (!is_executable(self::FACEDETECT_COMMAND)) {
            return;
        }
        $commandStr = self::FACEDETECT_COMMAND." ".$image->getOriginalFile();
        $output = $this->execute($commandStr);
        if (empty($output)) {
            return;
        }
        foreach ((array)$output as $outputLine) {
            $geometry = explode(" ", $outputLine);
            if (count($geometry) == 4) {
                list($geometryX, $geometryY, $geometryW, $geometryH) = $geometry;
                $cropCmdStr = self::IM_MOGRIFY_COMMAND.
                    " -gravity NorthWest -region {$geometryW}x{$geometryH}+{$geometryX}+{$geometryY} ".
                    "-scale '10%' -scale '1000%' ".
                    $image->getOriginalFile();
                $this->execute($cropCmdStr);
            }
        }
    }

    /**
     * Generate Command string bases on options
     *
     * @param Image $image
     */
    public function generateCmdString(Image $image)
    {
        $strip = $image->extract('strip');
        $thread = $image->extract('thread');
        $resize = $image->extract('resize');
        $frame = $image->extract('gif-frame');

        list($size, $extent, $gravity) = $this->generateSize($image);

        // we default to thumbnail
        $resizeOperator = $resize ? 'resize' : 'thumbnail';
        $command = [];
        $command[] = self::IM_CONVERT_COMMAND;
        $tmpFileName = $image->getOriginalFile();

        //Check the image is gif
        if ($image->isGifSupport()) {
            $command[] = '-coalesce';
            if ($image->getOutputExtension() != Image::EXT_GIF) {
                $tmpFileName .= '['.escapeshellarg($frame).']';
            }
        }

        $command[] = " ".$tmpFileName;
        $command[] = ' -'.$resizeOperator.' '.
            $size.$gravity.$extent.
            ' -colorspace sRGB';

        foreach ($image->getOptions() as $key => $value) {
            if (!empty($value) && !in_array($key, self::EXCLUDED_IM_OPTIONS)) {
                $command[] = "-{$key} ".escapeshellarg($value);
            }
        }

        // strip is added internally by ImageMagick when using -thumbnail
        if (!empty($strip)) {
            $command[] = "-strip ";
        }

        if (!empty($thread)) {
            $command[] = "-limit thread ".escapeshellarg($thread);
        }

        $command = $this->applyQuality($image, $command);

        $commandStr = implode(' ', $command);
        $image->setCommandString($commandStr);
    }

    /**
     * Apply the Quality processor based on options
     *
     * @param Image $image
     * @param array $command
     *
     * @return array
     */
    protected function applyQuality(Image $image, array $command): array
    {
        $quality = $image->extract('quality');
        /** WebP format */
        if (is_executable(self::CWEBP_COMMAND) && $image->isWebPSupport()) {
            $lossLess = $image->extract('webp-lossless') ? 'true' : 'false';
            $command[] = "-quality ".escapeshellarg($quality).
                " -define webp:lossless=".$lossLess." ".escapeshellarg($image->getNewFilePath());
        } /** MozJpeg compression */
        elseif (is_executable(self::MOZJPEG_COMMAND) && $image->isMozJpegSupport()) {
            $command[] = "TGA:- | ".escapeshellarg(self::MOZJPEG_COMMAND)
                ." -quality ".escapeshellarg($quality)
                ." -outfile ".escapeshellarg($image->getNewFilePath())
                ." -targa";
        } /** default ImageMagick compression */
        else {
            $command[] = "-quality ".escapeshellarg($quality).
                " ".escapeshellarg($image->getNewFilePath());
        }

        return $command;
    }

    /**
     * Size and Crop logic
     *
     * @param Image $image
     *
     * @return array
     */
    protected function generateSize(Image $image): array
    {
        $targetWidth = $image->extract('width');
        $targetHeight = $image->extract('height');

        $size = $extent = '';
        if ($targetWidth) {
            $size .= (string)escapeshellarg($targetWidth);
        }
        if ($targetHeight) {
            $size .= (string)'x'.escapeshellarg($targetHeight);
        }

        // When width and height a whole bunch of special cases must be taken into consideration.
        // resizing constraints (< > ^ !) can only be applied to geometry with both width AND height
        $preserveNaturalSize = $image->extract('preserve-natural-size');
        $preserveAspectRatio = $image->extract('preserve-aspect-ratio');

        if ($targetWidth && $targetHeight) {
            $extent = ' -extent '.$size;
            $gravity = ' -gravity '.escapeshellarg($image->extract('gravity'));
            $resizingConstraints = '';
            if ($image->extract('crop')) {
                $resizingConstraints .= '^';
                /**
                 * still need to solve the combination of ^
                 * -extent and +repage . Will need to do calculations with the
                 * original image dimensions vs. the target dimensions.
                 */
            } else {
                $extent .= '+repage ';
            }
            $resizingConstraints .= $preserveAspectRatio ? '' : '!';
            $size .= $resizingConstraints;
        } else {
            $size .= $preserveNaturalSize ? '\>' : '';
            $gravity = '';
        }
        //In cas on png format, remove extent option
        if ($image->isPngSupport()) {
            $extent = '';
        }

        return [$size, $extent, $gravity];
    }


    /**
     * Get the image Identity information
     *
     * @param Image $image
     *
     * @return string
     */
    public function getImageIdentity(Image $image): string
    {
        $output = $this->execute(self::IM_IDENTITY_COMMAND." ".$image->getNewFilePath());

        return !empty($output[0]) ? $output[0] : "";
    }

    /**
     * @param string $commandStr
     *
     * @return array
     * @throws \Exception
     */
    protected function execute(string $commandStr): array
    {
        exec($commandStr, $output, $code);
        if (count($output) === 0) {
            $outputError = $code;
        } else {
            $outputError = implode(PHP_EOL, $output);
        }

        if ($code !== 0) {
            throw new AppException(
                "Command failed. The exit code: ".
                $outputError."<br>The last line of output: ".
                $commandStr
            );
        }

        return $output;
    }
}
