<?php

namespace Flyimg\Image\Processor\FaceDetection;

use Flyimg\Image\Geometry\Point;
use Flyimg\Image\Geometry\Rectangle;
use Flyimg\Image\ImageInterface;
use Flyimg\Image\InputImageInterface;
use Flyimg\Image\LocalImageInterface;
use Flyimg\Image\TemporaryFileImage;
use Symfony\Component\Process\Process;

class FacePositionToGeometry
{
    /**
     * @var string
     */
    private $command;

    /**
     * @param string $command
     */
    public function __construct(
        string $command = '/usr/local/bin/facedetect'
    ) {
        $this->command = $command;
    }

    /**
     * @param ImageInterface $input
     *
     * @return Rectangle[]
     */
    public function detect(ImageInterface $input): array
    {
        if (!$input instanceof LocalImageInterface) {
            $input = TemporaryFileImage::fromFile($input);
        }

        $process = new Process([
            $this->command,
            $input->path()
        ]);

        $process->run();

        return array_map(function(string $line) {
            $coordinates = explode(' ', $line, 4);

            return new Rectangle(
                new Point($coordinates[0], $coordinates[1]),
                new Point($coordinates[0] + $coordinates[2], $coordinates[1] + $coordinates[3])
            );
        }, explode(PHP_EOL, $process->getOutput()));
    }
}
