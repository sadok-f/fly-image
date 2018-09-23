<?php

namespace Flyimg\FilterExpression\AST\Node;

class FilterNode implements NodeInterface
{
    /**
     * @var string
     */
    public $name;

    /**
     * @var ScalarNodeInterface[]
     */
    public $arguments;

    /**
     * @param FilterNameNode        $name
     * @param ScalarNodeInterface[] $arguments
     */
    public function __construct(FilterNameNode $name, array $arguments = [])
    {
        $this->name = $name;
        $this->arguments = $arguments;
    }
}
