<?php

namespace Flyimg\FilterExpression\Lexer;

final class Cursor
{
    /** @var int */
    public $offset;

    /** @var int */
    public $line;

    /** @var int */
    public $column;

    /**
     * @param int $offset
     * @param int $line
     * @param int $column
     */
    public function __construct(int $offset = 0, int $line = 0, int $column = 0)
    {
        $this->offset = $offset;
        $this->line = $line;
        $this->column = $column;
    }

    /**
     * @param string $match
     */
    public function update(string $match)
    {
        $this->offset += $length = mb_strlen($match);
        $this->line += mb_substr_count($match, "\n");
        if (($latestNewline = mb_strrpos($match, "\n")) !== false) {
            $this->column = $length - $latestNewline;
        } else {
            $this->column += $length;
        }
    }
}
