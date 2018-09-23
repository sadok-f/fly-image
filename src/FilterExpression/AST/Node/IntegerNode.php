<?php

namespace Flyimg\FilterExpression\AST\Node;

class IntegerNode implements ScalarNodeInterface
{
    /**
     * @var int
     */
    public $value;

    /**
     * @param int $value
     */
    public function __construct(int $value)
    {
        $this->value = $value;
    }
}
