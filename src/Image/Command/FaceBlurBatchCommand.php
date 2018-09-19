<?php

namespace Flyimg\Image\Command;

use Flyimg\Image\CommandChain;
use Flyimg\Image\ImageInterface;
use Flyimg\Image\FaceDetection\FacePositionToGeometry;
use Flyimg\Process\ProcessContext;

class FaceBlurBatchCommand implements CommandInterface
{
    /**
     * @var FacePositionToGeometry
     */
    private $faceDetector;

    /**
     * @var ProcessContext|null
     */
    private $context;

    /**
     * @param FacePositionToGeometry $faceDetector
     * @param ProcessContext   $context
     */
    public function __construct(
        FacePositionToGeometry $faceDetector,
        ProcessContext $context
    ) {
        $this->faceDetector = $faceDetector;
        $this->context = $context;
    }

    public function execute(ImageInterface $input): ImageInterface
    {
        $executor = new CommandChain();

        foreach ($this->faceDetector->detect($input) as $rectangle) {
            $executor->add(new BlurCommand($rectangle, $this->context));
        }

        return $executor->execute($input);
    }
}
