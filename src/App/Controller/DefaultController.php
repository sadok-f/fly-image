<?php

namespace Flyimg\App\Controller;

use Flyimg\Image\Command\FaceDetectBatchCommand;
use Flyimg\Image\Command\FacePixelateBatchCommand;
use Flyimg\Image\CommandChain;
use Flyimg\Image\FaceDetection\FacedetectShell;
use Imagine\Imagick\Imagine;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

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
        $imagine = new Imagine();
        $chain = new CommandChain(
            new FaceDetectBatchCommand(
                $imagine,
                new FacedetectShell(),
                10
            )/*,
            new FacePixelateBatchCommand(
                $imagine,
                new Facedetect(),
                10
            )*/
        );

        $source = $imagine->open($imageSrc);

        $source = $chain->execute($source);

        return new Response($source->get($source->metadata()['format']), 200, [
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
