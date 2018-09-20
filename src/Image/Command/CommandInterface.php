<?php

namespace Flyimg\Image\Command;

use Imagine\Image\ImageInterface;

interface CommandInterface
{
    public function execute(ImageInterface $input): ImageInterface;
}
