<?php

namespace Flyimg\Image;

use League\Flysystem\File;

final class TemporaryFileImage implements ImageInterface
{
    /**
     * @var string
     */
    private $path;

    public function __construct()
    {
        $this->path = tempnam();
    }

    public function file(): ?File
    {
        return null;
    }

    public function asStream()
    {
        return fopen($this->path, 'w+');
    }

    public function asString(): string
    {
        return file_get_contents($this->path);
    }
}
