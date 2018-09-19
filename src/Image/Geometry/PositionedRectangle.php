<?php

namespace Flyimg\Image\Geometry;

class PositionedRectangle implements PolygonInterface
{
    /**
     * @var Point
     */
    public $point;

    /**
     * @var int
     */
    public $width;

    /**
     * @var int
     */
    public $height;

    /**
     * @param Point $point
     * @param int $width
     * @param int $height
     */
    public function __construct(Point $point, int $width, $height)
    {
        $this->point = $point;
        $this->width = $width;
        $this->height = $height;
    }

    public function width(): int
    {
        return $this->width;
    }

    public function height(): int
    {
        return $this->height;
    }

    public function topLeft(): Point
    {
        return new Point(
            $this->point->x,
            $this->point->y
        );
    }

    public function topRight(): Point
    {
        return new Point(
            $this->point->x + $this->width,
            $this->point->y
        );
    }

    public function bottomLeft(): Point
    {
        return new Point(
            $this->point->x,
            $this->point->y + $this->height
        );
    }

    public function bottomRight(): Point
    {
        return new Point(
            $this->point->x + $this->width,
            $this->point->y + $this->height
        );
    }
}
