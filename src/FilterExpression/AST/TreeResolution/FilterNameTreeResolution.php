<?php

namespace Flyimg\FilterExpression\AST\TreeResolution;

use Flyimg\FilterExpression\AST\Node;
use Flyimg\FilterExpression\AST\TokenConstraint;
use Flyimg\FilterExpression\AST\TokenStream;

class FilterNameTreeResolution implements TreeResolutionInterface
{
    public function constraints(): iterable
    {
        return [
            TokenConstraint::identifier(),
        ];
    }

    public function assert(TokenStream $tokenStream): bool
    {
        return $tokenStream->assert(...$this->constraints());
    }

    public function create(TokenStream $tokenStream): Node\NodeInterface
    {
        $filterName = new Node\FilterNameNode(
            $tokenStream->expect(TokenConstraint::identifier())->value
        );

        while ($tokenStream->assert(TokenConstraint::point())) {
            $tokenStream->consume();

            $filterName->add(
                $tokenStream->expect(TokenConstraint::identifier())->value
            );
        }

        return $filterName;
    }
}
