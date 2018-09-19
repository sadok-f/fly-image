<?php

namespace Flyimg\Image;

interface RemoteImageInterface extends ImageInterface
{
    public function url(): string;
}
