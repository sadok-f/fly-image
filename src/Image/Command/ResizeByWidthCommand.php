<?php

namespace Flyimg\Image\Command;

use Imagine\Image\Box;
use Imagine\Image\ImageInterface;
use Imagine\Image\ImagineInterface;

class ResizeByWidthCommand implements CommandInterface
{
    /**
     * @var ImagineInterface
     */
    private $imagine;

    /**
     * @var int
     */
    private $width;

    /**
     * @param ImagineInterface $imagine
     * @param int             $width
     */
    public function __construct(
        ImagineInterface $imagine,
        int $width
    ) {
        $this->imagine = $imagine;
        $this->width = $width;
    }

    public function execute(ImageInterface $input): ImageInterface
    {
        $currentSize = $input->getSize();
        $box = new Box(
            $this->width,
            $currentSize->getHeight() * ($this->width / $currentSize->getWidth())
        );

        return $input->resize($box);
    }
}
