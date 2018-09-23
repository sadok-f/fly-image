<?php

namespace Flyimg\FilterExpression\Lexer;

final class Lexer
{
    private static $tokens = [
        Token::CHAIN => '/\\s*(-?>|,)(?=\\s*|$)/',
        Token::POINT => '/\\s*(\\.)(?=\\s*|$)/',
        Token::OPENING_BRACKET => '/(\\()(?=\\s*|$)/',
        Token::CLOSING_BRACKET => '/(\\))(?=\\s*|$)/',
        Token::NUMBER_FLOAT => '/\\s*(0|[+-]?([1-9][0-9]*)?\\.[0-9]*)(?=\\s*|$)/',
        Token::NUMBER_INTEGER => '/\\s*(0|[+-]?[1-9][0-9]*)(?=\\s*|$)/',
        Token::SINGLE_QUOTED_STRING => '/\\s*\'(([^\'\\\\]|\\\\\\\\|\\\\\'|\\\\)+)\'?(?=\\s*|$)/',
        Token::DOUBLE_QUOTED_STRING => '/\\s*"(([^"\\\\]|\\\\\\\\|\\\\"|\\\\)+)"?(?=\\s*|$)/',
        Token::IDENTIFIER => '/\\s*([^ \\t\\r\\n\\v\\f\\*\\(\\)\\{\\}:]+)(?=\\s*|$)/',
    ];

    /**
     * @param string $subject
     *
     * @return Token[]|iterable
     *
     * @throws \Exception
     */
    public function tokenize(string $subject): iterable
    {
        $length = strlen($subject);
        $cursor = new Cursor();
        while ($cursor->offset < $length) {
            $token = $this->match($subject, $cursor);
            if (null === $token) {
                throw new \Exception(strtr(
                    'Unable to parse subject "%subject%", unexpected input at offset %offset% (line %line%, column %column%)',
                    [
                        '%subject%' => mb_substr($subject, $cursor->offset),
                        '%offset%' => $cursor->offset,
                        '%line%' => $cursor->line,
                        '%column%' => $cursor->column,
                    ]
                ));
            }
            yield $token;
        }
    }
    /**
     * @param string $subject
     * @param Cursor $cursor
     *
     * @return Token|null
     */
    public function match(string $subject, Cursor $cursor): ?Token
    {
        foreach (self::$tokens as $name => $pattern) {
            $matches = [];
            if (1 === preg_match($pattern.'A', $subject, $matches, 0, $cursor->offset)) {
                $token = new Token($name, strlen($matches[0]), $matches[1], $cursor->offset, $cursor->line + 1, $cursor->column);
                $cursor->update($matches[0]);
                return $token;
            }
        }
        return null;
    }
}
