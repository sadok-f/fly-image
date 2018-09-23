<?php

namespace Flyimg\Exception;

use Flyimg\FilterExpression\AST\TokenConstraint;
use Flyimg\FilterExpression\Lexer\Token;

class UnexpectedFilterExpressionTokenException extends \RuntimeException implements FlyimgException
{
    private static function renderConstraintString(TokenConstraint $constraint)
    {
        if ($constraint->value !== null) {
            return sprintf('%s("%s")', $constraint->token, $constraint->value);
        }

        return sprintf('%s(*)', $constraint->token);
    }

    /**
     * @param Token $token
     * @param TokenConstraint $expected
     *
     * @return UnexpectedFilterExpressionTokenException
     */
    public static function unmatchedConstraint(Token $token, TokenConstraint $expected): self
    {
        return new self(strtr(
            'Invalid token found line %line%:%column%, found %actual%, was expecting %expected%',
            [
                '%line%' => $token->line,
                '%column%' => $token->column,
                '%expected%' => self::renderConstraintString($expected),
                '%actual%' => sprintf('%s("%s")', $token->token, $token->value),
            ]
        ));
    }

    /**
     * @param Token $token
     * @param TokenConstraint[] $expectedVariants
     *
     * @return UnexpectedFilterExpressionTokenException
     */
    public static function unmatchedConstraints(Token $token, TokenConstraint ...$expectedVariants): self
    {
        return new self(strtr(
            'Invalid token found line %line%:%column%, found %actual%, was expecting one of: %expected%',
            [
                '%line%' => $token->line,
                '%column%' => $token->column,
                '%actual%' => sprintf('%s(%s)', $token->token, $token->value),
                '%expected%' => implode(', ', array_map(function(TokenConstraint $expected) {
                    return self::renderConstraintString($expected);
                }, $expectedVariants))
            ]
        ));
    }

    /**
     * @param Token $token
     *
     * @return UnexpectedFilterExpressionTokenException
     */
    public static function expectedString(Token $token): self
    {
        return self::unmatchedConstraints($token,
            new TokenConstraint(Token::SINGLE_QUOTED_STRING),
            new TokenConstraint(Token::DOUBLE_QUOTED_STRING)
        );
    }

    /**
     * @param Token $token
     *
     * @return UnexpectedFilterExpressionTokenException
     */
    public static function expectedStringOrIdentifier(Token $token): self
    {
        return self::unmatchedConstraints($token,
            new TokenConstraint(Token::SINGLE_QUOTED_STRING),
            new TokenConstraint(Token::DOUBLE_QUOTED_STRING),
            new TokenConstraint(Token::IDENTIFIER)
        );
    }
}
