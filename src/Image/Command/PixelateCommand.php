<?php

namespace Flyimg\Image\Command;

use Imagine\Image\Box;
use Imagine\Image\BoxInterface;
use Imagine\Image\ImageInterface;
use Imagine\Image\ImagineInterface;
use Imagine\Image\PointInterface;

class PixelateCommand implements CommandInterface
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
     * @var BoxInterface
     */
    private $compactor;

    /**
     * @var int
     */
    private $dimension;

    /**
     * @param BoxInterface     $box
     * @param PointInterface   $start
     * @param ImagineInterface $imagine
     * @param int              $dimension
     */
    public function __construct(
        ImagineInterface $imagine,
        PointInterface $start,
        BoxInterface $box,
        int $dimension = 10
    ) {
        $this->imagine = $imagine;
        $this->box = $box;
        $this->compactor = new Box(
            ceil($this->box->getWidth() / $dimension),
            ceil($this->box->getHeight() / $dimension)
        );
        $this->start = $start;
        $this->dimension = $dimension;
    }

    public function execute(ImageInterface $input): ImageInterface
    {
        $temporary = $input->copy();
        $temporary
            ->crop($this->start, $this->box)
            ->resize($this->compactor)
            ->resize($this->box, ImageInterface::FILTER_BOX);

        return $input->paste($temporary, $this->start);
    }
}
