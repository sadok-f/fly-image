<?php

namespace Flyimg\FilterExpression\Lexer;

final class Token
{
    const CHAIN = 'T_CHAIN';
    const POINT = 'T_POINT';
    const IDENTIFIER = 'T_IDENTIFIER';
    const OPENING_BRACKET = 'T_OPENING_BRACKET';
    const CLOSING_BRACKET = 'T_CLOSING_BRACKET';
    const NUMBER_INTEGER = 'T_NUMBER_INTEGER';
    const NUMBER_FLOAT = 'T_NUMBER_FLOAT';
    const SINGLE_QUOTED_STRING = 'T_SINGLE_QUOTED_STRING';
    const DOUBLE_QUOTED_STRING = 'T_DOUBLE_QUOTED_STRING';

    /** @var string */
    public $token;

    /** @var int */
    public $size;

    /** @var string */
    public $value;

    /** @var int */
    public $offset;

    /** @var int */
    public $line;

    /** @var int */
    public $column;

    /**
     * @param string $token
     * @param int $size
     * @param string $value
     * @param int $offset
     * @param int $line
     * @param int $column
     */
    public function __construct(string $token, int $size, string $value, int $offset, int $line, int $column)
    {
        $this->token = $token;
        $this->size = $size;
        $this->value = $value;
        $this->offset = $offset;
        $this->line = $line;
        $this->column = $column;
    }
}
