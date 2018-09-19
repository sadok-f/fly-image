<?php

namespace Flyimg\Image;

use League\Flysystem\File;
use League\Flysystem\FilesystemInterface;

final class FlysystemImage implements ImageInterface
{
    /**
     * @var File
     */
    private $file;

    /**
     * @param File $file
     */
    public function __construct(File $file)
    {
        $this->file = $file;
    }

    public static function fromImage(
        ImageInterface $image,
        FilesystemInterface $filesystem,
        string $path
    ): self {
        $file = new File($filesystem, $path);

        $file->writeStream($image->asStream());

        return new self($file);
    }

    public function file(): ?File
    {
        return $this->file;
    }

    public function asStream()
    {
        return $this->file->readStream();
    }

    public function asString(): string
    {
        return $this->file->read();
    }
}
