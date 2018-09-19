<?php

namespace Flyimg\Image;

use League\Flysystem\File;

final class LocalFileImage implements LocalImageInterface
{
    /**
     * @var string
     */
    private $path;

    public function __construct(string $path)
    {
        $this->path = $path;
    }

    public function path(): string
    {
        return $this->path;
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
