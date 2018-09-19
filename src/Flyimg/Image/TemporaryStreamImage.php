<?php

namespace Flyimg\Image;

use League\Flysystem\File;

final class TemporaryStreamImage implements ImageInterface
{
    /**
     * @var resource
     */
    private $stream;

    public function __construct()
    {
        $this->stream = fopen('php://temp', 'w+');
    }

    public function file(): ?File
    {
        return null;
    }

    public function asStream()
    {
        fseek($this->stream, 0, SEEK_SET);
        return $this->stream;
    }

    public function asString(): string
    {
        fseek($this->stream, 0, SEEK_SET);
        return stream_get_contents($this->stream);
    }
}
