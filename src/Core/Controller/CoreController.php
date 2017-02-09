<?php

namespace Core\Controller;

use Core\Entity\Image;
use Core\Service\ImageProcessor;
use Silex\Application;
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
     * @return ImageProcessor
     */
    public function getImageProcessor()
    {
        return $this->app['image.processor'];
    }

    /**
     * @param       $templateName
     * @param array $params
     * @return Response
     */
    public function render($templateName, $params = [])
    {
        $body = $this->app['twig']->render('@Core/'.$templateName, $params);

        return new Response($body);
    }

    /**
     * @param Image $image
     * @return Response
     */
    public function generateImageResponse(Image $image)
    {
        $response = new Response();
        $response->setContent($image->getContent());
        $response = $this->setHeadersContent($image, $response);
        $image->unlinkUsedFiles();

        return $response;
    }

    /**
     * @param Image $image
     * @return Response
     */
    public function generatePathResponse(Image $image)
    {
        $response = new Response();
        $imagePath = $image->getNewFileName();
        $imagePath = sprintf($this->app['flysystems']['file_path_resolver'], $imagePath);
        $response->setContent($imagePath);
        $image->unlinkUsedFiles();

        return $response;
    }

    /**
     * @param Image    $image
     * @param Response $response
     * @return Response
     */
    protected function setHeadersContent(Image $image, Response $response)
    {
        $response->headers->set('Content-Type', $image->getResponseContentType());

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

            $response->headers->set('im-identify', $this->app['image.processor']->getImageIdentity($image));
            $response->headers->set('im-command', $image->getCommandString());
        }

        return $response;
    }
}
