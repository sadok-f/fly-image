<?php

namespace TestsCore\Resolver;

use Core\Exception\InvalidArgumentException;
use Symfony\Bundle\FrameworkBundle\Tests\Functional\WebTestCase;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ControllerResolverTests extends WebTestCase
{
    /**
     */
    public function testClassNotExist()
    {
        $this->expectException(InvalidArgumentException::class);

        $client = static::createClient();
        $client->request('GET', '/UndefinedClass');
    }

    /**
     */
    public function testControllerNotExist()
    {
        $this->expectException(InvalidArgumentException::class);

        $client = static::createClient();
        $client->request('GET', '/UndefinedController');
    }

    /**
     */
    public function testUndefinedRoute()
    {
        $this->expectException(NotFoundHttpException::class);

        $client = static::createClient();
        $client->request('GET', '/UndefinedRoute');
    }
}
