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
     * @param array      $params
     * @param Filesystem $filesystem
     */
    public function __construct($params, Filesystem $filesystem)
    {
        $this->params = $params;
        $this->filesystem = $filesystem;
    }

    /**
     * @param      $options
     * @param null $imageSrc
     * @return Image
     * @throws \Exception
     */
    public function process($options, $imageSrc = null)
    {
        $image = new Image($options, $imageSrc, $this->params);

        try {
            $this->checkRestrictedDomains($image);

            if ($this->filesystem->has($image->getNewFileName()) && $image->getOptions()['refresh']) {
                $this->filesystem->delete($image->getNewFileName());
            }
            if (!$this->filesystem->has($image->getNewFileName())) {
                $this->saveNewFile($image);
            }

            $image->setContent($this->filesystem->read($image->getNewFileName()));
        } catch (\Exception $e) {
            $image->unlinkUsedFiles();
            throw $e;
        }

        return $image;
    }

    /**
     * Save new FileName based on source file and list of options
     *
     * @param Image $image
     * @throws \Exception
     */
    protected function saveNewFile(Image $image)
    {
        $faceCrop = $image->extractByKey('face-crop');
        $faceCropPosition = $image->extractByKey('face-crop-position');
        $faceBlur = $image->extractByKey('face-blur');

        $this->generateCmdString($image);

        if ($faceBlur) {
            $this->processBlurringFaces($image);
        }

        if ($faceCrop) {
            $this->processCroppingFaces($image, $faceCropPosition);
        }

        $this->execute($image->getCommandString());

        if ($this->filesystem->has($image->getNewFileName())) {
            $this->filesystem->delete($image->getNewFileName());
        }

        $this->filesystem->write($image->getNewFileName(), stream_get_contents(fopen($image->getNewFilePath(), 'r')));
    }

    /**
     * Face detection cropping
     *
     * @param Image $image
     * @param int   $faceCropPosition
     */
    protected function processCroppingFaces(Image $image, $faceCropPosition = 0)
    {
        if (!is_executable(self::FACEDETECT_COMMAND)) {
            return;
        }
        $commandStr = self::FACEDETECT_COMMAND." ".$image->getTemporaryFile();
        $output = $this->execute($commandStr);
        if (empty($output[$faceCropPosition])) {
            return;
        }
        $geometry = explode(" ", $output[$faceCropPosition]);
        if (count($geometry) == 4) {
            list($geometryX, $geometryY, $geometryW, $geometryH) = $geometry;
            $cropCmdStr =
                self::IM_CONVERT_COMMAND.
                " '{$image->getTemporaryFile()}' -crop {$geometryW}x{$geometryH}+{$geometryX}+{$geometryY} ".
                $image->getTemporaryFile();
            $this->execute($cropCmdStr);
        }
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
        $commandStr = self::FACEDETECT_COMMAND." ".$image->getTemporaryFile();
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
                    $image->getTemporaryFile();
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
        $strip = $image->extractByKey('strip');
        $thread = $image->extractByKey('thread');
        $resize = $image->extractByKey('resize');

        list($size, $extent, $gravity) = $this->generateSize($image);

        // we default to thumbnail
        $resizeOperator = $resize ? 'resize' : 'thumbnail';
        $command = [];
        $command[] = self::IM_CONVERT_COMMAND.
            " ".$image->getTemporaryFile().
            ' -'.$resizeOperator.' '.$size.$gravity.$extent.
            ' -colorspace sRGB';

        if (!empty($thread)) {
            $command[] = "-limit thread ".escapeshellarg($thread);
        }

        // strip is added internally by ImageMagick when using -thumbnail
        if (!empty($strip)) {
            $command[] = "-strip ";
        }

        foreach ($image->getOptions() as $key => $value) {
            if (!empty($value) && !in_array($key, self::EXCLUDED_IM_OPTIONS)) {
                $command[] = "-{$key} ".escapeshellarg($value);
            }
        }

        $command = $this->applyQuality($image, $command);

        $commandStr = implode(' ', $command);
        $image->setCommandString($commandStr);
    }

    /**
     * Apply the Quality processor based on options
     *
     * @param Image $image
     * @param       $command
     * @return array
     */
    protected function applyQuality(Image $image, $command)
    {
        $quality = $image->extractByKey('quality');
        /** WebP format */
        if (is_executable(self::CWEBP_COMMAND) && $image->isWebPSupport()) {
            $lossLess = $image->extractByKey('webp-lossless') ? 'true' : 'false';
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
     * @return array
     */
    protected function generateSize(Image $image)
    {
        $targetWidth = $image->extractByKey('width');
        $targetHeight = $image->extractByKey('height');
        $crop = $image->extractByKey('crop');

        $size = '';

        if ($targetWidth) {
            $size .= (string)escapeshellarg($targetWidth);
        }
        if ($targetHeight) {
            $size .= (string)'x'.escapeshellarg($targetHeight);
        }

        // When width and height a whole bunch of special cases must be taken into consideration.
        // resizing constraints (< > ^ !) can only be applied to geometry with both width AND height
        $preserveNaturalSize = $image->extractByKey('preserve-natural-size');
        $preserveAspectRatio = $image->extractByKey('preserve-aspect-ratio');
        $gravityValue = $image->extractByKey('gravity');
        $extent = '';
        $gravity = '';

        if ($targetWidth && $targetHeight) {
            $extent = ' -extent '.$size;
            $gravity = ' -gravity '.escapeshellarg($gravityValue);
            $resizingConstraints = '';
            $resizingConstraints .= $preserveNaturalSize ? '\>' : '';
            if ($crop) {
                $resizingConstraints .= '^';
                /**
                 * still need to solve the combination of ^
                 * -extent and +repage . Will need to do calculations with the
                 * original image dimentions vs. the target dimentions.
                 */
            } else {
                $extent .= '+repage ';
            }
            $resizingConstraints .= $preserveAspectRatio ? '' : '!';
            $size .= $resizingConstraints;
        } else {
            $size .= $preserveNaturalSize ? '\>' : '';
        }
        //In cas on png format, remove extent option
        if ($image->isPngSupport()) {
            $extent = '';
        }

        return [$size, $extent, $gravity];
    }


    /**
     * Get the image Identity information
     * @param Image $image
     * @return string
     */
    public function getImageIdentity(Image $image)
    {
        $output = $this->execute(self::IM_IDENTITY_COMMAND." ".$image->getNewFilePath());

        return !empty($output[0]) ? $output[0] : "";
    }

    /**
     * @param $commandStr
     * @return string
     * @throws \Exception
     */
    protected function execute($commandStr)
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

    /**
     * Check Restricted Domain enabled
     * @param Image $image
     * @throws AppException
     */
    protected function checkRestrictedDomains(Image $image)
    {
        //check restricted_domains is enabled
        if ($this->params['restricted_domains'] &&
            is_array($this->params['whitelist_domains']) &&
            !in_array(parse_url($image->getSourceFile(), PHP_URL_HOST), $this->params['whitelist_domains'])
        ) {
            throw  new AppException(
                'Restricted domains enabled, the domain your fetching from is not allowed: '.
                parse_url($image->getSourceFile(), PHP_URL_HOST)
            );
        }
    }

    public function unlinkUsedFiles()
    {
    }
}
