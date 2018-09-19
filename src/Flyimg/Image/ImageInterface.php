<?php

namespace Flyimg\Image;

use League\Flysystem\File;

interface ImageInterface
{
    /**
     * @return File
     */
    public function file(): ?File;

    /**
     * @return resource
     */
    public function asStream();

    /**
     * @return string
     */
    public function asString(): string;
}
