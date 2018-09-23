<?php

namespace Flyimg\FilterExpression\AST;

use Flyimg\FilterExpression\AST\Node;
use Flyimg\FilterExpression\AST\TreeResolution;
use Flyimg\FilterExpression\Lexer\Token;

class Tree
{
    /**
     * @var TreeResolution\FilterChainTreeResolution
     */
    private $filterChainResolution;

    public function __construct()
    {
        $this->filterChainResolution = new TreeResolution\FilterChainTreeResolution(
            new TreeResolution\FilterTreeResolution(
                new TreeResolution\FilterNameTreeResolution()
            )
        );
    }

    /**
     * @param Token[]|iterable $tokens
     *
     * @return Node\NodeInterface[]|iterable
     */
    public function compile(iterable $tokens): iterable
    {
        if (is_array($tokens)) {
            $tokens = new \ArrayIterator($tokens);
        }

        if ($tokens instanceof \IteratorAggregate) {
            $tokens = $tokens->getIterator();
        }

        $tokenStream = new TokenStream($tokens);
        if ($tokenStream->finished()) {
            return [];
        }

        return new \ArrayIterator([
            $this->filterChainResolution->create($tokenStream)
        ]);
    }
}
