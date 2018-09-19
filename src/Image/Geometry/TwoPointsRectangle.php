<?php

namespace Flyimg\Image\Geometry;

class TwoPointsRectangle implements PolygonInterface
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

    public function topLeft(): Point
    {
        return new Point(
            $this->topLeft->x,
            $this->topLeft->y
        );
    }

    public function topRight(): Point
    {
        return new Point(
            $this->bottomRight->x,
            $this->topLeft->y
        );
    }

    public function bottomLeft(): Point
    {
        return new Point(
            $this->topLeft->x,
            $this->bottomRight->y
        );
    }

    public function bottomRight(): Point
    {
        return new Point(
            $this->bottomRight->x,
            $this->bottomRight->y
        );
    }
}
