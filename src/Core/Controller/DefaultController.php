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
        return $this->render('Default/index.twig');
    }

    /**
     * @param      $options
     * @param null $imageSrc
     * @return Response
     */
    public function uploadAction($options, $imageSrc = null)
    {
        try {
            $image = $this->getImageProcessor()->process($options, $imageSrc);
        } catch (\Exception $e) {
            return new Response($e->getMessage(), Response::HTTP_FORBIDDEN);
        }

        return $this->generateImageResponse($image);
    }

    /**
     * @param      $options
     * @param null $imageSrc
     * @return Response
     */
    public function pathAction($options, $imageSrc = null)
    {
        try {
            $image = $this->getImageProcessor()->process($options, $imageSrc);
        } catch (\Exception $e) {
            return new Response($e->getMessage(), Response::HTTP_FORBIDDEN);
        }

        return $this->generatePathResponse($image);
    }
}
