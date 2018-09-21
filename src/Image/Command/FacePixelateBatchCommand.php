<?php

namespace Flyimg\Image\Command;

use Flyimg\Image\CommandChain;
use Flyimg\Image\FaceDetection\FaceDetectionInterface;
use Imagine\Image\BoxInterface;
use Imagine\Image\ImageInterface;
use Imagine\Image\ImagineInterface;
use Imagine\Image\PointInterface;

class FacePixelateBatchCommand implements CommandInterface
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
    private $dimension;

    /**
     * @param ImagineInterface       $imagine
     * @param FaceDetectionInterface $faceDetector
     * @param int                    $dimension
     */
    public function __construct(
        ImagineInterface $imagine,
        FaceDetectionInterface $faceDetector,
        int $dimension = 10
    ) {
        $this->imagine = $imagine;
        $this->faceDetector = $faceDetector;
        $this->dimension = $dimension;
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
                new PixelateCommand($this->imagine, $point, $box, $this->dimension)
            );
        }

        return $executor->execute($input);
    }
}
