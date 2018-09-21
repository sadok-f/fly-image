<?php

namespace Flyimg\Http\OptionResolver;

use Flyimg\Image\Command\CommandFactoryInterface;
use Flyimg\Image\CommandChain;

class PathResolver implements OptionResolverInterface
{
    /**
     * @var callable[]
     */
    private $optionFactories;

    /**
     * @var string
     */
    private $optionSeparator;

    /**
     * @var string
     */
    private $valueSeparator;

    /**
     * @param callable[] $optionFactories
     * @param string     $requestParameterName
     * @param string     $optionSeparator
     * @param string     $valueSeparator
     */
    public function __construct(
        array $optionFactories,
        string $requestParameterName = 'options',
        string $optionSeparator = ',',
        string $valueSeparator = '_'
    ) {
        $this->optionFactories = $optionFactories;
        $this->optionSeparator = $optionSeparator;
        $this->valueSeparator = $valueSeparator;
    }

    public function resolve(string $rawOptions): CommandChain
    {
        $options = $this->walkOptionStrings(
            explode($this->optionSeparator, $rawOptions)
        );

        $chain = new CommandChain();

        foreach ($options as $option => $values) {
            if (!isset($this->optionFactories[$option])) {
                continue;
            }

            $chain->add($this->optionFactories[$option]->build(...$values));
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
