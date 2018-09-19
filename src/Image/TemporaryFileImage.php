<?php

namespace Flyimg\Image;

use Flyimg\Exception\ReadFileException;
use League\Flysystem\File;

final class TemporaryFileImage implements LocalImageInterface
{
    /**
     * @var string
     */
    private $directory;

    /**
     * @var string
     */
    private $prefix;

    /**
     * @var string
     */
    private $path;

    /**
     * @var resource
     */
    private $stream;

    public function __construct(string $directory = null, string $prefix = 'flyimg.')
    {
        if ($directory === null) {
            $directory = sys_get_temp_dir();
        }

        $this->directory = $directory;
        $this->prefix = $prefix;
        $this->path = tempnam($directory, $prefix);
        $this->stream = null;
    }

    public function __clone()
    {
        $this->path = tempnam($this->directory, $this->prefix);
        $this->stream = null;
    }

    public function __destruct()
    {
        if (is_resource($this->stream)) {
            fclose($this->stream);
            $this->stream = null;
        }

        unlink($this->path);
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
        if ($this->stream === null || !is_resource($this->stream)) {
            if (!($this->stream = @fopen($this->path, 'w+', false))) {
                throw new ReadFileException(strtr(
                    'Error occurred while trying to read the file at path: %path%',
                    [
                        '%path%' => $this->path,
                    ]
                ));
            }
        }

        return $this->stream;
    }

    public function asString(): string
    {
        return stream_get_contents($this->asStream());
    }
}
