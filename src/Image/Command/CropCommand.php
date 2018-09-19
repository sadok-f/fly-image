<?php

namespace Flyimg\Image\Command;

use Flyimg\Exception\ExecFailedException;
use Flyimg\Image\Geometry\PolygonInterface;
use Flyimg\Image\ImageInterface;
use Flyimg\Image\LocalImageInterface;
use Flyimg\Image\TemporaryFileImage;
use Flyimg\Process\ProcessContext;

class CropCommand implements CommandInterface
{
    /**
     * @var PolygonInterface
     */
    private $dimensions;

    /**
     * @var ProcessContext|null
     */
    private $context;

    /**
     * @param PolygonInterface $dimensions
     * @param ProcessContext   $context
     */
    public function __construct(
        PolygonInterface $dimensions,
        ProcessContext $context
    ) {
        $this->dimensions = $dimensions;
        $this->context = $context;
    }

    public function execute(ImageInterface $input): ImageInterface
    {
        $output = TemporaryFileImage::fromFile($input);
        if (!$input instanceof LocalImageInterface) {
            $input = TemporaryFileImage::fromFile($input);
        }

        $process = $this->context->pipe(
            '/usr/bin/convert',
            '-crop', self::normalizeGeometry($this->dimensions),
            '-write', $output->getPath(),
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

    private static function normalizeGeometry(PolygonInterface $dimensions): string
    {
        return strtr(
            '%width%x%height%+%abscissa%+%ordinate%',
            [
                '%width%' => $dimensions->width(),
                '%height%' => $dimensions->height(),
                '%abscissa%' => $dimensions->topLeft()->x,
                '%ordinate%' => $dimensions->topLeft()->y,
            ]
        );
    }
}
