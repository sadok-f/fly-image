<?php

namespace Flyimg\Image;

interface LocalImageInterface extends ImageInterface
{
    public function path(): string;
}
