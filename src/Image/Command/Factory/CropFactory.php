<?php

namespace Flyimg\Image\Command\Factory;

use Flyimg\Image\Command\CommandFactoryInterface;
use Flyimg\Image\Command\CommandInterface;
use Flyimg\Image\Command\CropCommand;
use Imagine\Image\Box;
use Imagine\Image\ImagineInterface;
use Imagine\Image\Point;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class CropFactory implements CommandFactoryInterface
{
    /**
     * @var ImagineInterface
     */
    private $imagine;

    /**
     * @var ValidatorInterface
     */
    private $validator;

    /**
     * @param ImagineInterface $imagine
     */
    public function __construct(ImagineInterface $imagine)
    {
        $this->imagine = $imagine;
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
                new Assert\GreaterThanOrEqual(1)
            ]),
            new Assert\All([
                new Assert\Type('int'),
                new Assert\GreaterThanOrEqual(1)
            ]),
            new Assert\All([
                new Assert\Type('int'),
                new Assert\GreaterThanOrEqual(1)
            ]),
            new Assert\All([
                new Assert\Type('int'),
                new Assert\GreaterThanOrEqual(1)
            ])
        ];
    }

    public function build(...$options): CommandInterface
    {
        if (!$this->validator->validate($options, $this->constraints())) {
            throw new \RuntimeException(
                'Failed to validate the arguments constraints.'
            );
        }

        return new CropCommand($this->imagine, ...$this->toCommandArguments(...$options));
    }

    private function toCommandArguments(int $x, int $y, int $width, int $height): \Generator
    {
        yield new Point($x, $y);
        yield new Box($width, $height);
    }
}
