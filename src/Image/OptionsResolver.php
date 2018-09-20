<?php

namespace Flyimg\Image;

class OptionsResolver
{
    /**
     * @var string[]
     */
    private $optionKeys;

    /**
     * @var string
     */
    private $optionSeparator;

    /**
     * @param string[] $optionKeys
     * @param string   $optionSeparator
     */
    public function __construct(array $optionKeys, string $optionSeparator = ',')
    {
        $this->optionKeys = $optionKeys;
        $this->optionSeparator = $optionSeparator;
    }

    public function resolve(string $path): array
    {
        return iterator_to_array(
            $this->walkOptionStrings(
                explode($this->optionSeparator, $path)
            )
        );
    }

    /**
     * @param string[] $optionStrings
     *
     * @return \Generator
     */
    private function walkOptionStrings(array $optionStrings): \Generator
    {
        foreach ($optionStrings as $optionString) {
            [$optionKey, $optionValue] = explode('=', $optionString, 2);

            yield $optionKey => $optionValue;
        }
    }
}
