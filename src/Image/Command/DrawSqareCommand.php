<?php

namespace Flyimg\Image\Command;

use Imagine\Image\Box;
use Imagine\Image\BoxInterface;
use Imagine\Image\ImageInterface;
use Imagine\Image\ImagineInterface;
use Imagine\Image\Palette\Color\ColorInterface;
use Imagine\Image\Point;
use Imagine\Image\PointInterface;

class DrawSqareCommand implements CommandInterface
{
    /**
     * @var ImagineInterface
     */
    private $imagine;

    /**
     * @var PointInterface
     */
    private $start;

    /**
     * @var BoxInterface
     */
    private $box;

    /**
     * @var ColorInterface
     */
    private $color;

    /**
     * @var int
     */
    private $thickness;

    /**
     * @param ImagineInterface $imagine
     * @param BoxInterface     $box
     * @param PointInterface   $start
     * @param ColorInterface   $color
     * @param int              $thickness
     */
    public function __construct(
        ImagineInterface $imagine,
        PointInterface $start,
        BoxInterface $box,
        ColorInterface $color,
        int $thickness = 1
    ) {
        $this->imagine = $imagine;
        $this->start = $start;
        $this->box = $box;
        $this->color = $color;
        $this->thickness = $thickness;
    }

    public function execute(ImageInterface $input): ImageInterface
    {
        $input
            ->draw()
            ->polygon(
                [
                    new Point($this->start->getX(), $this->start->getY()),
                    new Point($this->start->getX() + $this->box->getWidth(), $this->start->getY()),
                    new Point($this->start->getX() + $this->box->getWidth(), $this->start->getY() + $this->box->getHeight()),
                    new Point($this->start->getX(), $this->start->getY() + $this->box->getHeight()),
                ],
                $this->color,
                false,
                $this->thickness
            );

        return $input;
    }
}
