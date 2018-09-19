<?php

namespace Flyimg\Image\Command;

use Flyimg\Image\ImageInterface;

interface CommandInterface
{
    public function execute(ImageInterface $input): ImageInterface;
}
