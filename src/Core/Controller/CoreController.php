<?php

namespace Core\Controller;

use Core\Entity\Image;
use Core\Service\ImageProcessor;
use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class CoreController
{
    /**
     * @var Application
     */
    protected $app;

    /**
     * @param Application $app
     */
    public function setApp(Application $app)
    {
        $this->app = $app;
    }

    /**
     * @param $templateName
     * @param array $params
     * @return Response
     */
    public function render($templateName, $params = [])
    {
        $body = $this->app['twig']->render('@Core/' . $templateName, $params);
        return new Response($body);
    }

    /**
     * @param Image $image
     * @param mixed $imageContent
     * @return Response
     */
    public function generateImageResponse(Image $image, $imageContent)
    {
        $response = new Response();
        $response->setContent($imageContent);
        $response = $this->setHeadersContent($image, $response);
        $image->unlinkUsedFiles();
        return $response;
    }

    /**
     * @param Image $image
     * @param Response $response
     * @return Response
     */
    protected function setHeadersContent(Image $image, Response $response)
    {
        $response->headers->set('Content-Type', $image->getResponseContentType());

        /** @todo: move all this cache header logic to its own method  */
        $expireDate = new \DateTime();
        $expireDate->add(new \DateInterval('P1Y'));
        $response->setExpires($expireDate);
        $longCacheTime = 3600 * 24 * ((int)$this->app['params']['header_cache_days']);

        $response->setMaxAge($longCacheTime);
        $response->setSharedMaxAge($longCacheTime);
        $response->setPublic();

        if ($image->getOptions()['refresh']) {
            $response->headers->set('Cache-Control', 'no-cache, private');
            $response->setExpires(null)->expire();

            $response->headers->set('im-identify', $image->getInfo());
            $response->headers->set('im-command', $image->getCommandString());
        }
        return $response;
    }
}
