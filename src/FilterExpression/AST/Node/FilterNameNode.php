<?php

namespace Flyimg\FilterExpression\AST\Node;

class FilterNameNode implements NodeInterface
{
    /**
     * @var string[]
     */
    public $nameParts;

    /**
     * @param string[] $nameParts
     */
    public function __construct(string ...$nameParts)
    {
        $this->nameParts = $nameParts;
    }

    /**
     * @param string[] $nameParts
     */
    public function add(string ...$nameParts)
    {
        $this->nameParts = array_merge($this->nameParts, $nameParts);
    }

    public function __toString()
    {
        return implode('.', $this->nameParts);
    }
}
