<?php

namespace Flyimg\Image\Command;

use Flyimg\Image\CommandChain;
use Flyimg\Image\FaceDetection\FaceDetectionInterface;
use Imagine\Image\BoxInterface;
use Imagine\Image\ImageInterface;
use Imagine\Image\ImagineInterface;
use Imagine\Image\Palette\Color\RGB as RGBColor;
use Imagine\Image\Palette\RGB as RGBPalette;
use Imagine\Image\PointInterface;

class FaceDetectBatchCommand implements CommandInterface
{
    /**
     * @var ImagineInterface
     */
    private $imagine;

    /**
     * @var FaceDetectionInterface
     */
    private $faceDetector;

    /**
     * @var int
     */
    private $thickness;

    /**
     * @param ImagineInterface       $imagine
     * @param FaceDetectionInterface $faceDetector
     * @param int                    $thickness
     */
    public function __construct(
        ImagineInterface $imagine,
        FaceDetectionInterface $faceDetector,
        int $thickness = 10
    ) {
        $this->imagine = $imagine;
        $this->faceDetector = $faceDetector;
        $this->thickness = $thickness;
    }

    public function execute(ImageInterface $input): ImageInterface
    {
        $executor = new CommandChain();

        /**
         * @var PointInterface $point
         * @var BoxInterface $box
         */
        foreach ($this->faceDetector->detect($input) as [$point, $box]) {
            $executor->add(
                new DrawSqareCommand(
                    $this->imagine,
                    $point,
                    $box,
                    new RGBColor(new RGBPalette(), [255, 127, 0], 50),
                    $this->thickness
                )
            );
        }

        return $executor->execute($input);
    }
}
