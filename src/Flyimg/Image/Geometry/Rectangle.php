<?php

namespace Flyimg\Image\Geometry;

class Rectangle
{
    /**
     * @var Point
     */
    public $topLeft;

    /**
     * @var Point
     */
    public $bottomRight;

    /**
     * @param Point $topLeft
     * @param Point $bottomRight
     */
    public function __construct(Point $topLeft, Point $bottomRight)
    {
        $this->topLeft = $topLeft;
        $this->bottomRight = $bottomRight;
    }

    public function width(): int
    {
        return $this->bottomRight->x - $this->topLeft->x;
    }

    public function height(): int
    {
        return $this->bottomRight->y - $this->topLeft->y;
    }
}
