<?php

namespace Flyimg\Image\Command;

use Flyimg\Image\ImageInterface;
use Flyimg\Image\LocalImageInterface;
use Flyimg\Image\TemporaryFileImage;
use Symfony\Component\Process\Process;

class ResizeCommand implements CommandInterface
{
    /**
     * @var int
     */
    private $width;

    /**
     * @var int
     */
    private $height;

    /**
     * @param int $width
     * @param int $height
     */
    public function __construct(
        int $width,
        int $height
    ) {
        $this->width = $width;
        $this->height = $height;
    }

    public function execute(ImageInterface $input): ImageInterface
    {
        $output = TemporaryFileImage::fromFile($input);
        if (!$input instanceof LocalImageInterface) {
            $input = TemporaryFileImage::fromFile($input);
        }

        $process = new Process([
            '/usr/bin/convert',
            '-crop', self::normalizeSize($this->width, $this->height),
            '-write', $output->path(),
            $input->path(),
        ]);

        $process->run();

        return $output;
    }

    private static function normalizeSize(int $width, int $height): string
    {
        return strtr(
            '%width%x%height%',
            [
                '%width%' => $width,
                '%height%' => $height,
            ]
        );
    }
}
