<?php

namespace Flyimg\App\Controller;

use Flyimg\App\OptionResolver\PathResolver;
use Flyimg\Image\FaceDetection\FacedetectShell;
use Imagine\Exception as ImagineException;
use Flyimg\Exception as FlyimgException;
use Imagine\Imagick\Imagine;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class PathExpressionController extends Controller
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
        $resolver = PathResolver::buildStandard($imagine, new FacedetectShell());

        try {
            $source = $imagine->open($imageSrc);

            $source = $resolver->resolve($options)->execute($source);
        } catch (ImagineException\InvalidArgumentException $e) {
            return new Response('', 404);
        } catch (FlyimgException\InvalidArgumentException $e) {
            return new Response($e->getMessage(), 400);
        }

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
