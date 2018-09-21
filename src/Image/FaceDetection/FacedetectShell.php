<?php

namespace Flyimg\Image\FaceDetection;

use Flyimg\Image\ShortLivedStreamedFile;
use Imagine\Image\Box;
use Imagine\Image\ImageInterface;
use Imagine\Image\Point;
use Symfony\Component\Process\Process;

class FacedetectShell implements FaceDetectionInterface
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
     * @return \Generator|array<PointInterface => BoxInterface>
     */
    public function detect(ImageInterface $input): \Generator
    {
        $path = $input->metadata()['filepath'];
        $needCleanup = false;
        if (empty($path)) {
            $path = sys_get_temp_dir() . '/flyimg.' . uniqid() . '.jpeg';
            $input->copy()->save($path);
            $needCleanup = true;
        }

        $process = new Process([$this->command, $path]);

        $process->run();

        foreach (explode(PHP_EOL, $process->getOutput()) as $line) {
            if (empty($line)) {
                continue;
            }

            $coordinates = explode(' ', $line, 4);

            yield [new Point($coordinates[0], $coordinates[1]), new Box($coordinates[2], $coordinates[3])];
        }

        if ($needCleanup) {
            unlink($path);
        }
    }
}
