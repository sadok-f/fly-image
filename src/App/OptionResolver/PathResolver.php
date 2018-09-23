<?php

namespace Flyimg\App\OptionResolver;

use Flyimg\Exception\InvalidArgumentException;
use Flyimg\Image\Command\CommandFactoryInterface;
use Flyimg\Image\CommandChain;
use Flyimg\Image\Command\Factory as CommandFactory;
use Flyimg\Image\FaceDetection\FaceDetectionInterface;
use Imagine\Image\ImagineInterface;

class PathResolver implements OptionResolverInterface
{
    /**
     * @var CommandFactoryInterface[]
     */
    private $commandFactories;

    /**
     * @var string
     */
    private $optionSeparator;

    /**
     * @var string
     */
    private $valueSeparator;

    /**
     * @param CommandFactoryInterface[] $commandFactories
     * @param string                    $optionSeparator
     * @param string                    $valueSeparator
     */
    public function __construct(
        array $commandFactories,
        string $optionSeparator = ',',
        string $valueSeparator = '_'
    ) {
        $this->commandFactories = $commandFactories;
        $this->optionSeparator = $optionSeparator;
        $this->valueSeparator = $valueSeparator;
    }

    public static function buildStandard(
        ImagineInterface $imagine,
        FaceDetectionInterface $faceDetection,
        string $optionSeparator = ',',
        string $valueSeparator = '_'
    ): self {
        return new static(
            [
                'r' => new CommandFactory\ResizeFactory($imagine),
                'w' => new CommandFactory\ResizeByWidthFactory($imagine),
                'h' => new CommandFactory\ResizeByHeightFactory($imagine),
                'c' => new CommandFactory\CropFactory($imagine),
                'b' => new CommandFactory\BlurFactory($imagine),
                'sqr' => new CommandFactory\DrawSquareFactory($imagine),
                'fb' => new CommandFactory\FaceBlurBatchFactory($imagine, $faceDetection),
                'fd' => new CommandFactory\FaceDetectBatchFactory($imagine, $faceDetection),
                'fp' => new CommandFactory\FacePixelateBatchFactory($imagine, $faceDetection),
                'p' => new CommandFactory\PixelateFactory($imagine),
            ],
            $optionSeparator,
            $valueSeparator
        );
    }

    public function resolve(string $rawOptions): CommandChain
    {
        $options = $this->walkOptionStrings(
            explode($this->optionSeparator, $rawOptions)
        );

        $chain = new CommandChain();

        foreach ($options as $option => $values) {
            if (!isset($this->commandFactories[$option])) {
                throw new InvalidArgumentException(
                    'The specified action filter does not exist.'
                );
            }

            $chain->add($this->commandFactories[$option]->build(...$values));
        }

        return $chain;
    }

    /**
     * @param string[] $optionStrings
     *
     * @return \Generator
     */
    private function walkOptionStrings(array $optionStrings): \Generator
    {
        foreach ($optionStrings as $optionString) {
            $optionValues = explode($this->valueSeparator, $optionString);
            $optionKey = array_shift($optionValues);

            yield $optionKey => $optionValues;
        }
    }
}
