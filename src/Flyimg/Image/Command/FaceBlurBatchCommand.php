<?php

namespace Flyimg\Image\Command;

use Flyimg\Image\CommandChain;
use Flyimg\Image\ImageInterface;
use Flyimg\Image\Processor\FaceDetection\FacePositionToGeometry;

class FaceBlurBatchCommand implements CommandInterface
{
    /**
     * @var FacePositionToGeometry
     */
    private $faceDetector;

    /**
     * @param FacePositionToGeometry $faceDetector
     */
    public function __construct(
        FacePositionToGeometry $faceDetector
    ) {
        $this->faceDetector = $faceDetector;
    }

    public function execute(ImageInterface $input): ImageInterface
    {
        $executor = new CommandChain();

        foreach ($this->faceDetector->detect($input) as $rectangle) {
            $executor->add(new BlurCommand($rectangle));
        }

        return $executor->execute($input);
    }
}
