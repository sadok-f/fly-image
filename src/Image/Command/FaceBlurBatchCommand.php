<?php

namespace Flyimg\Image\Command;

use Flyimg\Image\CommandChain;
use Flyimg\Image\FaceDetection\FaceDetectionInterface;
use Imagine\Image\BoxInterface;
use Imagine\Image\ImageInterface;
use Imagine\Image\ImagineInterface;
use Imagine\Image\PointInterface;

class FaceBlurBatchCommand implements CommandInterface
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
     * @param ImagineInterface       $imagine
     * @param FaceDetectionInterface $faceDetector
     */
    public function __construct(
        ImagineInterface $imagine,
        FaceDetectionInterface $faceDetector
    ) {
        $this->imagine = $imagine;
        $this->faceDetector = $faceDetector;
    }

    public function execute(ImageInterface $input): ImageInterface
    {
        $executor = new CommandChain();

        /**
         * @var PointInterface $point
         * @var BoxInterface $box
         */
        foreach ($this->faceDetector->detect($input) as $point => $box) {
            $executor->add(
                new BlurCommand($this->imagine, $point, $box)
            );
        }

        return $executor->execute($input);
    }
}
