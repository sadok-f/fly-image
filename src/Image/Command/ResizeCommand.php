<?php

namespace Flyimg\Image\Command;

use Flyimg\Exception\ExecFailedException;
use Flyimg\Image\ImageInterface;
use Flyimg\Image\LocalImageInterface;
use Flyimg\Image\TemporaryFileImage;
use Flyimg\Process\ProcessContext;

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
     * @var ProcessContext|null
     */
    private $context;

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

        $process = $this->context->pipe(
            '/usr/bin/convert',
            '-crop', self::normalizeSize($this->width, $this->height),
            '-write', $output->path(),
            $input->path()
        )->build();

        $process->run();
        if (!$process->isSuccessful()) {
            throw new ExecFailedException(strtr(
                'The blur command did not run properly, message was: %message%.',
                [
                    '%message%' => $process->getErrorOutput(),
                ]
            ));
        }

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
