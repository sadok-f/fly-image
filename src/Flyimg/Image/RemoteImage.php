<?php

namespace Flyimg\Image;

use League\Flysystem\File;

final class RemoteImage implements ImageInterface
{
    /**
     * @var resource
     */
    private $stream;

    public function __construct(string $url)
    {
        $this->stream = fopen($url, 'r');
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
