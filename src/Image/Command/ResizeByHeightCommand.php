<?php

namespace Flyimg\Image\Command;

use Imagine\Image\Box;
use Imagine\Image\ImageInterface;
use Imagine\Image\ImagineInterface;

class ResizeByHeightCommand implements CommandInterface
{
    /**
     * @var ImagineInterface
     */
    private $imagine;

    /**
     * @var int
     */
    private $height;

    /**
     * @param ImagineInterface $imagine
     * @param int              $height
     */
    public function __construct(
        ImagineInterface $imagine,
        int $height
    ) {
        $this->imagine = $imagine;
        $this->height = $height;
    }

    public function execute(ImageInterface $input): ImageInterface
    {
        $currentSize = $input->getSize();
        $box = new Box(
            $currentSize->getWidth() * ($currentSize->getHeight() / $this->height),
            $this->height
        );

        return $input->thumbnail($box, ImageInterface::THUMBNAIL_INSET);
    }
}
