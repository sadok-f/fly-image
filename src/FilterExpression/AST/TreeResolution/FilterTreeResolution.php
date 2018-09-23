<?php

namespace Flyimg\FilterExpression\AST\TreeResolution;

use Flyimg\Exception;
use Flyimg\FilterExpression\AST\Node;
use Flyimg\FilterExpression\AST\TokenConstraint;
use Flyimg\FilterExpression\AST\TokenStream;

class FilterTreeResolution implements TreeResolutionInterface
{
    /**
     * @var FilterNameTreeResolution
     */
    private $filterNameResolution;

    /**
     * @param FilterNameTreeResolution $filterNameResolution
     */
    public function __construct(FilterNameTreeResolution $filterNameResolution)
    {
        $this->filterNameResolution = $filterNameResolution;
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
        $filterName = $this->filterNameResolution->create($tokenStream);

        if ($tokenStream->finished() ||
            !$tokenStream->assert(TokenConstraint::openingBracket())
        ) {
            return new Node\FilterNode($filterName);
        }

        $tokenStream->consume();

        $arguments = [];
        while (!$tokenStream->assert(TokenConstraint::closingBracket())) {
            if ($tokenStream->assert(...TokenConstraint::anyString())) {
                $arguments[] = new Node\StringNode($tokenStream->consume()->value);
            } else if ($tokenStream->assert(TokenConstraint::integer())) {
                $arguments[] = new Node\IntegerNode($tokenStream->consume()->value);
            } else if ($tokenStream->assert(TokenConstraint::float())) {
                $arguments[] = new Node\FloatNode($tokenStream->consume()->value);
            } else {
                throw Exception\UnexpectedFilterExpressionTokenException::expectedString($tokenStream->watch());
            }

            if ($tokenStream->assert(TokenConstraint::chain())) {
                $tokenStream->consume();
                continue;
            }
        }
        $tokenStream->expect(TokenConstraint::closingBracket());
        return new Node\FilterNode($filterName, $arguments);
    }
}
