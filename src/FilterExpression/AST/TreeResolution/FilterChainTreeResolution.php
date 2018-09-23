<?php

namespace Flyimg\FilterExpression\AST\TreeResolution;

use Flyimg\FilterExpression\AST\Node;
use Flyimg\FilterExpression\AST\TokenConstraint;
use Flyimg\FilterExpression\AST\TokenStream;

class FilterChainTreeResolution implements TreeResolutionInterface
{
    /**
     * @var FilterTreeResolution
     */
    private $filterResolution;

    /**
     * @param FilterTreeResolution $filterResolution
     */
    public function __construct(FilterTreeResolution $filterResolution)
    {
        $this->filterResolution = $filterResolution;
    }

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
        $filters = [];

        while (true) {
            $filters[] = $this->filterResolution->create($tokenStream);

            if ($tokenStream->finished()) {
                break;
            }

            $tokenStream->expect(TokenConstraint::chain());
        }

        return new Node\FilterChainNode($filters);
    }
}
