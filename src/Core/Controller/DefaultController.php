<?php

namespace Core\Controller;

use Core\Entity\Image;
use Symfony\Component\HttpFoundation\Response;

class DefaultController extends CoreController
{
    /**
     * @return string
     */
    public function indexAction()
    {
        return 'Flyimg: Hello from ' . $this->app->escape('Docker!');
    }

    /**
     * @param $options
     * @param null $imageSrc
     * @return Response
     */
    public function uploadAction($options, $imageSrc = null)
    {
        /** @var \Core\Service\ImageProcessor $manager */
        $manager = $this->app['image.processor'];
        $image = new Image($options, $imageSrc, $this->app['params']);
        try {
            $imageContent = $manager->process($image);
        } catch (\Exception $e) {
            $imageContent = null;
            $image->unlinkUsedFiles();
            $formattedMessage = '<pre>' . $e->getMessage() . '</pre>';
            return new Response($formattedMessage, Response::HTTP_FORBIDDEN);
        }

        return $this->generateImageResponse($image, $imageContent);
    }
}
