<?php

namespace Flyimg\Image\Command;

use Imagine\Image\BoxInterface;
use Imagine\Image\ImageInterface;
use Imagine\Image\ImagineInterface;
use Imagine\Image\PointInterface;

class ResizeCommand implements CommandInterface
{
    /**
     * @var ImagineInterface
     */
    private $imagine;

    /**
     * @var BoxInterface
     */
    private $box;

    /**
     * @param ImagineInterface $imagine
     * @param BoxInterface     $box
     */
    public function __construct(
        ImagineInterface $imagine,
        BoxInterface $box
    ) {
        $this->imagine = $imagine;
        $this->box = $box;
    }

    public function execute(ImageInterface $input): ImageInterface
    {
        return $input->resize($this->box);
    }
}
