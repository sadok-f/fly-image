<?php

namespace Flyimg\FilterExpression\AST\Node;

class FilterChainNode implements NodeInterface
{
    /**
     * @var FilterNode[]
     */
    public $filters;

    /**
     * @param FilterNode[] $filters
     */
    public function __construct(array $filters)
    {
        $this->filters = $filters;
    }
}
