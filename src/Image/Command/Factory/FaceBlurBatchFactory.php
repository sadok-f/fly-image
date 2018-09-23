<?php

namespace Flyimg\Image\Command\Factory;

use Flyimg\Image\Command\CommandInterface;
use Flyimg\Image\Command\FaceBlurBatchCommand;
use Flyimg\Image\FaceDetection\FaceDetectionInterface;
use Imagine\Image\ImagineInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class FaceBlurBatchFactory
{
    /**
     * @var ImagineInterface
     */
    private $imagine;

    /**
     * @var FaceDetectionInterface
     */
    private $faceDetection;

    /**
     * @var ValidatorInterface
     */
    private $validator;

    /**
     * @param ImagineInterface $imagine
     * @param FaceDetectionInterface $faceDetection
     */
    public function __construct(
        ImagineInterface $imagine,
        FaceDetectionInterface $faceDetection
    ) {
        $this->imagine = $imagine;
        $this->faceDetection = $faceDetection;
        $this->validator = Validation::createValidator();
    }

    /**
     * @return Constraint[]
     */
    private function constraints(): array
    {
        return [];
    }

    public function build(...$options): CommandInterface
    {
        if (!$this->validator->validate($options, $this->constraints())) {
            throw new \RuntimeException(
                'Failed to validate the arguments constraints.'
            );
        }

        return new FaceBlurBatchCommand($this->imagine, $this->faceDetection);
    }
}
