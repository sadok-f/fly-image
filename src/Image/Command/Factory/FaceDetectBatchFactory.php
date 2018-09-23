<?php

namespace Flyimg\Image\Command\Factory;

use Flyimg\Image\Command\CommandInterface;
use Flyimg\Image\Command\FaceDetectBatchCommand;
use Flyimg\Image\FaceDetection\FaceDetectionInterface;
use Imagine\Image\ImagineInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class FaceDetectBatchFactory
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
        return [
            new Assert\All([
                new Assert\Type('int'),
                new Assert\GreaterThanOrEqual(1),
            ]),
        ];
    }

    public function build(...$options): CommandInterface
    {
        if (!$this->validator->validate($options, $this->constraints())) {
            throw new \RuntimeException(
                'Failed to validate the arguments constraints.'
            );
        }

        return new FaceDetectBatchCommand($this->imagine, $this->faceDetection, ...$this->toCommandArguments(...$options));
    }

    private function toCommandArguments(int $thickness): \Generator
    {
        yield $thickness;
    }
}
