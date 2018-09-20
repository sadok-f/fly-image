<?php

namespace Flyimg\App\Command;

use Flyimg\Image\Command\BlurCommand;
use Flyimg\Image\Command\FaceBlurBatchCommand;
use Flyimg\Image\Command\PixelateCommand;
use Flyimg\Image\CommandChain;
use Flyimg\Image\FaceDetection\FacePositionToGeometry;
use Flyimg\Image\Geometry\Point;
use Flyimg\Image\Geometry\PositionedRectangle;
use Flyimg\Image\RemoteURLImage;
use Flyimg\Process\ProcessContext;
use Imagine\Image\Box;
use Imagine\Imagick\Imagine;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class DownloadCommand extends ContainerAwareCommand
{
    /**
     * @var string|null The default command name
     */
    protected static $defaultName = 'flyimg:download';

    protected function configure()
    {
        $this
            ->addArgument('url', InputArgument::IS_ARRAY)
            ->addOption('width', 'W', InputOption::VALUE_OPTIONAL, 'Resize image to specified width, in pixels.')
            ->addOption('height', 'H', InputOption::VALUE_OPTIONAL, 'Resize image to specified height, in pixels.')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $imagine = new Imagine();
        $chain = new CommandChain(
            new PixelateCommand(
                $imagine,
                new \Imagine\Image\Point(50, 50),
                new Box(250, 250)
            ),
            new BlurCommand(
                $imagine,
                new \Imagine\Image\Point(350, 50),
                new Box(250, 250)
            ),
            new FaceBlurBatchCommand(
                $imagine,
                new FacePositionToGeometry()
            )
        );

        foreach ($input->getArgument('url') as $imageSrc) {
            $source = $imagine->open($imageSrc);

            $source = $chain->execute($source);

            $source->save($filename = (__DIR__ . '/' . uniqid('flyimg.') . '.png'));

            $output->writeln($filename);
        }
    }
}
