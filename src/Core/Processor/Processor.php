<?php

namespace Core\Processor;

use Core\Entity\Command;
use Core\Entity\Image\OutputImage;
use Core\Exception\ExecFailedException;

/**
 * Class Processor
 * @package Core\Processor
 */
class Processor
{
    /** MozJPEG bin path */
    public const MOZJPEG_COMMAND = '/opt/mozjpeg/cjpeg';

    /** ImageMagick bin path*/
    public const IM_CONVERT_COMMAND = '/usr/local/bin/magick';
    public const IM_IDENTITY_COMMAND = '/usr/local/bin/identify';
    public const IM_MOGRIFY_COMMAND = '/usr/local/bin/mogrify';

    /** CWEBP bin path */
    public const CWEBP_COMMAND = '/usr/bin/cwebp';

    /** FaceDetect bin path */
    public const FACEDETECT_COMMAND = '/usr/bin/python3 /var/www/html/python/facedetect.py';

    /** Smart Crop script */
    public const SMARTCROP_COMMAND = '/usr/bin/python3 /var/www/html/python/smartcrop.py';

    /** Ffmpeg bin path */
    public const FFMPEG_COMMAND = '/usr/bin/ffmpeg';

    /** OutputImage options excluded from IM command */
    public const EXCLUDED_IM_OPTIONS = ['quality', 'mozjpeg', 'refresh', 'webp-lossless'];

    /**
     * @param Command $command
     *
     * @return array
     * @throws \Exception
     */
    public function execute(Command $command): array
    {
        exec($command . ' 2>&1', $output, $code);
        if (count($output) === 0) {
            $outputError = $code;
        } else {
            $outputError = implode(PHP_EOL, $output);
        }

        if ($code !== 0) {
            throw new ExecFailedException(
                "Command failed.\nThe exit code: " .
                    $outputError . "\nThe last line of output: " .
                    $command
            );
        }

        return $output;
    }

    /**
     * Get the image Identity information
     *
     * @param OutputImage $image
     *
     * @return string
     */
    public function imageIdentityInformation(OutputImage $image): string
    {
        $identityCmd = new Command(self::IM_IDENTITY_COMMAND);
        $identityCmd->addArgument($image->getOutputTmpPath());
        $output = $this->execute($identityCmd);

        return !empty($output[0]) ? $output[0] : "";
    }
}
