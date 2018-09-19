<?php

namespace Flyimg\Image\Command;

use Flyimg\Image\Geometry\Rectangle;
use Flyimg\Image\ImageInterface;
use Flyimg\Image\TemporaryFileImage;
use Symfony\Component\Process\Process;

class CropCommand implements CommandInterface
{
    /**
     * @var Rectangle
     */
    private $dimensions;

    /**
     * @param Rectangle $dimensions
     */
    public function __construct(
        Rectangle $dimensions
    ) {
        $this->dimensions = $dimensions;
    }

    public function execute(ImageInterface $input): ImageInterface
    {
        $output = TemporaryFileImage::fromFile($input);

        $process = new Process([
            '/usr/bin/convert',
            '-crop', self::normalizeGeometry($this->dimensions),
            '-write', $output->getPath(),
            $input->sourcePath(),
        ]);

        $process->run();

        return $output;
    }

    private static function normalizeGeometry(Rectangle $dimensions): string
    {
        return strtr(
            '%width%x%height%+%abscissa%+%ordinate%',
            [
                '%width%' => $dimensions->width(),
                '%height%' => $dimensions->height(),
                '%abscissa%' => $dimensions->topLeft->x,
                '%ordinate%' => $dimensions->topLeft->y,
            ]
        );
    }
}
