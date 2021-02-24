<?php

namespace Core\Processor;

use Core\Entity\Command;
use Core\Entity\Image\InputImage;
use Core\Entity\Image\OutputImage;

/**
 * Class ExtractProcessor
 * @package Core\Processor
 */
class ExtractProcessor extends Processor
{
    /**
     * Extract a portion of the image based on coordinates
     *
     * @param OutputImage $outputImage
     * @throws \Exception
     */
    public function extract(OutputImage $outputImage)
    {
        if (!is_executable(self::IM_CONVERT_COMMAND)) {
            return;
        }

        $inputImage = $outputImage->getInputImage();
        $topLeftX = $outputImage->extractKey('extract-top-x');
        $topLeftY = $outputImage->extractKey('extract-top-y');
        $bottomRightX = $outputImage->extractKey('extract-bottom-x');
        $bottomRightY = $outputImage->extractKey('extract-bottom-y');
        $gravity = $outputImage->extractKey('gravity');

        $geometryW = $bottomRightX - $topLeftX;
        $geometryH = $bottomRightY - $topLeftY;
        $extractCmd = new Command(self::IM_CONVERT_COMMAND);
        $extractCmd->addArgument($inputImage->sourceImagePath());
        $extractCmd->addArgument(" -gravity", "$gravity");
        $extractCmd->addArgument(" -crop", "{$geometryW}x{$geometryH}+{$topLeftX}+{$topLeftY}");
        $extractCmd->addArgument($inputImage->sourceImagePath());
        $this->execute($extractCmd);
    }
}
