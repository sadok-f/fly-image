<?php

namespace Flyimg\FilterExpression\AST\TreeResolution;

use Flyimg\FilterExpression\AST\Node;
use Flyimg\FilterExpression\AST\TokenConstraint;
use Flyimg\FilterExpression\AST\TokenStream;

interface TreeResolutionInterface
{
    /**
     * @return TokenConstraint[]|iterable
     */
    public function constraints(): iterable;

    /**
     * @param TokenStream $tokenStream
     *
     * @return bool
     */
    public function assert(TokenStream $tokenStream): bool;

    /**
     * @param TokenStream $tokenStream
     *
     * @return Node\NodeInterface
     */
    public function create(TokenStream $tokenStream): Node\NodeInterface;
}
