<?php

namespace Flyimg\Image\Command\Factory;

use Flyimg\Image\Command\CommandFactoryInterface;
use Flyimg\Image\Command\CommandInterface;
use Flyimg\Image\Command\DrawSqareCommand;
use Imagine\Image\Box;
use Imagine\Image\ImagineInterface;
use Imagine\Image\Palette\Color;
use Imagine\Image\Palette;
use Imagine\Image\Point;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class DrawSquareFactory implements CommandFactoryInterface
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
                new Assert\GreaterThanOrEqual(1),
            ]),
            new Assert\All([
                new Assert\Type('int'),
                new Assert\GreaterThanOrEqual(1),
            ]),
            new Assert\All([
                new Assert\Type('int'),
                new Assert\GreaterThanOrEqual(1),
            ]),
            new Assert\All([
                new Assert\Type('int'),
                new Assert\GreaterThanOrEqual(0),
                new Assert\LessThanOrEqual(255),
            ]),
            new Assert\All([
                new Assert\Type('int'),
                new Assert\GreaterThanOrEqual(0),
            ]),
            new Assert\All([
                new Assert\Type('int'),
                new Assert\GreaterThanOrEqual(0),
            ]),
            new Assert\All([
                new Assert\Type('int'),
                new Assert\GreaterThanOrEqual(0),
                new Assert\LessThanOrEqual(255),
            ]),
            new Assert\Optional([
                new Assert\Type('int'),
                new Assert\GreaterThanOrEqual(0),
                new Assert\LessThanOrEqual(100),
            ]),
            new Assert\Optional([
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

        return new DrawSqareCommand($this->imagine, ...$this->toCommandArguments(...$options));
    }

    private function toCommandArguments(int $x, int $y, int $width, int $height, int $red, int $green, int $blue, int $alpha, int $thickness): \Generator
    {
        yield new Point($x, $y);
        yield new Box($width, $height);
        yield new Color\RGB(new Palette\RGB(), [$red, $green, $blue], $alpha);
        yield $thickness;
    }
}
