<?php

namespace Flyimg\Image;

use League\Flysystem\File;

final class TemporaryFileImage implements LocalImageInterface
{
    /**
     * @var string
     */
    private $path;

    public function __construct(string $directory = null, string $prefix = 'flyimg.')
    {
        if ($directory === null) {
            $directory = sys_get_temp_dir();
        }

        $this->path = tempnam($directory, $prefix);
    }

    public static function fromFile(
        ImageInterface $source,
        string $directory = null,
        string $prefix = 'flyimg.'
    ) {
        $destination = new self($directory, $prefix);

        if ($source instanceof LocalImageInterface) {
            $destination->path = $source->path();
        } else {
            stream_copy_to_stream($source->asStream(), $destination->asStream());
        }

        return $destination;
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
