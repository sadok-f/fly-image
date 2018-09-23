<?php

namespace Flyimg\FilterExpression\AST\Node;

class FloatNode implements ScalarNodeInterface
{
    /**
     * @var float
     */
    public $value;

    /**
     * @param float $value
     */
    public function __construct(float $value)
    {
        $this->value = $value;
    }
}
