<?php

namespace Flyimg\Image\Geometry;

interface PolygonInterface
{
    public function topLeft(): Point;
    public function topRight(): Point;

    public function bottomLeft(): Point;
    public function bottomRight(): Point;

    public function width(): int;

    public function height(): int;
}
