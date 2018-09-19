<?php

namespace Flyimg\Image;

use Flyimg\Exception\ReadFileException;
use League\Flysystem\File;

final class RemoteURLImage implements RemoteImageInterface
{
    /**
     * @var string
     */
    private $url;

    /**
     * @var resource
     */
    private $stream;

    public function __construct(string $url)
    {
        $this->url = $url;
    }

    public function url(): string
    {
        return $this->url;
    }

    public function file(): ?File
    {
        return null;
    }

    public function asStream()
    {
        if ($this->stream === null || !is_resource($this->stream)) {
            $context = stream_context_create([
                'http' => [
                    'method' => 'GET',
                    'max_redirects' => '0',
                ],
            ]);

            if (!$this->stream = @fopen($this->url, 'r', false, $context)) {
                throw new ReadFileException(strtr(
                    'Error occurred while trying to read the file at URL: %url%',
                    [
                        '%url%' => $this->url,
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
