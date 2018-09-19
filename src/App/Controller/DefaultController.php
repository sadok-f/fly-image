<?php

namespace Flyimg\App\Controller;

use Flyimg\Image\Command\BlurCommand;
use Flyimg\Image\CommandChain;
use Flyimg\Image\Geometry\Point;
use Flyimg\Image\Geometry\PositionedRectangle;
use Flyimg\Image\RemoteURLImage;
use Flyimg\Image\TemporaryStreamImage;
use Flyimg\Process\ProcessContext;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\StreamedResponse;

class DefaultController extends Controller
{
    /**
     * @return Response
     */
    public function indexAction(): Response
    {
        return $this->render('Default/index.html.twig');
    }

    /**
     * @param string $options
     * @param string $imageSrc
     *
     * @return Response
     */
    public function uploadAction(string $options, string $imageSrc = null): Response
    {
        $chain = new CommandChain(
            new BlurCommand(
                new PositionedRectangle(new Point(0, 0), 200, 200),
                new ProcessContext('docker-compose', 'exec', 'flyimg')
            )
        );

        $source = new RemoteURLImage($imageSrc);

        $source = $chain->execute($source);

        return new StreamedResponse(function() use($source) {
            $out = fopen('php://output', 'wb');

            stream_copy_to_stream($source->asStream(), $out);

            fclose($out);
        }, 200, [
            'Content-Type' => 'image/png',
        ]);
    }

    /**
     * @param string $options
     * @param string $imageSrc
     *
     * @return Response
     */
    public function pathAction(string $options, string $imageSrc = null): Response
    {
        return new Response($imageSrc);
    }
}
