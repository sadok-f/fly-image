<?php

namespace Flyimg\FilterExpression\AST;

use Flyimg\FilterExpression\Lexer\Token;

class TokenConstraint
{
    /**
     * @var string
     */
    public $token;

    /**
     * @var string|null
     */
    public $value;

    /**
     * @param string $token
     * @param null|string $value
     */
    public function __construct(string $token, ?string $value = null)
    {
        $this->token = $token;
        $this->value = $value;
    }

    /**
     * @return self[]|iterable
     */
    public static function anyString(): iterable
    {
        return [
            new self(Token::SINGLE_QUOTED_STRING),
            new self(Token::DOUBLE_QUOTED_STRING),
        ];
    }

    /**
     * @param string|null $name
     *
     * @return TokenConstraint
     */
    public static function identifier(?string $name = null): TokenConstraint
    {
        return new self(Token::IDENTIFIER, $name);
    }

    /**
     * @return self[]|iterable
     */
    public static function anyStringOrIdentifier(): iterable
    {
        return array_merge(
            self::anyString(),
            [
                self::identifier()
            ]
        );
    }

    /**
     * @return TokenConstraint
     */
    public static function chain(): TokenConstraint
    {
        return new self(Token::CHAIN);
    }

    /**
     * @return TokenConstraint
     */
    public static function point(): TokenConstraint
    {
        return new self(Token::POINT);
    }

    /**
     * @return TokenConstraint
     */
    public static function openingBracket(): TokenConstraint
    {
        return new self(Token::OPENING_BRACKET);
    }

    /**
     * @return TokenConstraint
     */
    public static function closingBracket(): TokenConstraint
    {
        return new self(Token::CLOSING_BRACKET);
    }

    /**
     * @return TokenConstraint
     */
    public static function integer(): TokenConstraint
    {
        return new self(Token::NUMBER_INTEGER);
    }

    /**
     * @return TokenConstraint
     */
    public static function float(): TokenConstraint
    {
        return new self(Token::NUMBER_FLOAT);
    }

    /**
     * @return self[]|iterable
     */
    public static function anyNumber(): iterable
    {
        return [
            new self(Token::NUMBER_FLOAT),
            new self(Token::NUMBER_INTEGER),
        ];
    }
}
