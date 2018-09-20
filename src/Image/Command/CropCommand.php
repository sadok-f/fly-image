<?php

namespace Flyimg\Image\Command;

use Imagine\Image\BoxInterface;
use Imagine\Image\ImageInterface;
use Imagine\Image\ImagineInterface;
use Imagine\Image\PointInterface;

class CropCommand implements CommandInterface
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
     * @param ImagineInterface $imagine
     * @param BoxInterface     $box
     * @param PointInterface   $start
     */
    public function __construct(
        ImagineInterface $imagine,
        PointInterface $start,
        BoxInterface $box
    ) {
        $this->imagine = $imagine;
        $this->box = $box;
        $this->start = $start;
    }

    public function execute(ImageInterface $input): ImageInterface
    {
        return $input->crop($this->start, $this->box);
    }
}
