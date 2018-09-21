<?php

namespace Flyimg\App\Controller;

use Flyimg\Http\OptionResolver\PathResolver;
use Flyimg\Image\Command\Factory as CommandFactory;
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
        $resolver = new PathResolver(
            [
                'w' => new CommandFactory\ResizeByWidthFactory($imagine),
                'h' => new CommandFactory\ResizeByHeightFactory($imagine),
            ]
        );

        $source = $imagine->open($imageSrc);

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
