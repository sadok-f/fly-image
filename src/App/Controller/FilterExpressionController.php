<?php

namespace Flyimg\App\Controller;

use Flyimg\App\OptionResolver\FilterResolver;
use Flyimg\Image\FaceDetection\FacedetectShell;
use Imagine\Exception\InvalidArgumentException;
use Imagine\Imagick\Imagine;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class FilterExpressionController extends Controller
{
    /**
     * @param string $options
     * @param string $imageSrc
     *
     * @return Response
     */
    public function uploadAction(string $options, string $imageSrc = null): Response
    {
        $imagine = new Imagine();
        $resolver = FilterResolver::buildStandard($imagine, new FacedetectShell());

        try {
            $source = $imagine->open($imageSrc);
        } catch (InvalidArgumentException $e) {
            return new Response('', 404);
        }

        $source = $resolver->resolve($options)->execute($source);

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
