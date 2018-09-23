<?php

namespace Flyimg\FilterExpression\AST\Node;

class StringNode implements ScalarNodeInterface
{
    /**
     * @var string
     */
    public $value;

    /**
     * @param string $value
     */
    public function __construct(string $value)
    {
        $this->value = $value;
    }
}
