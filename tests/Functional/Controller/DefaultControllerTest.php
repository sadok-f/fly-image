<?php

namespace Flyimg\Tests\Functional\Controller;

use Core\Exception\InvalidArgumentException;
use Core\Exception\ReadFileException;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Tests\Core\BaseTest;

class DefaultControllerTest extends WebTestCase
{
    public function testIndexAction()
    {
        $client = static::createClient();
        $client->request('GET', '/');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    public function testUploadAction()
    {
        $client = static::createClient();
        $client->request('GET', '/upload/c_0_0_200_200,rf_1,o_png/'.BaseTest::JPG_TEST_IMAGE);

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertFalse($client->getResponse()->isEmpty());
    }

    public function testUploadActionWebp()
    {
        $client = static::createClient();
        $client->request('GET', 'upload/o_webp/'.BaseTest::PNG_TEST_IMAGE);

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertFalse($client->getResponse()->isEmpty());
    }

    public function testUploadActionGif()
    {
        $client = static::createClient();
        $client->request('GET', 'upload/o_gif/'.BaseTest::PNG_TEST_IMAGE);

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertFalse($client->getResponse()->isEmpty());
    }

    public function testUploadActionWithFaceDetection()
    {
        $client = static::createClient();
        $client->request('GET', '/upload/fc_1/'.BaseTest::FACES_TEST_IMAGE);

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertFalse($client->getResponse()->isEmpty());
    }

    public function testUploadActionNotFound()
    {
        $client = static::createClient();
        $client->request('GET', '/upload/c_0_0_200_200/Rovinj-Croatia-nonExist.jpg');

        $this->assertEquals(404, $client->getResponse()->getStatusCode());
    }

    public function testUploadActionInvalidExtension()
    {
        $client = static::createClient();
        $client->request('GET', '/upload/c_0_0_200_200,o_xxx/'.BaseTest::JPG_TEST_IMAGE);

        $this->assertEquals(400, $client->getResponse()->getStatusCode());
    }

    public function testPathAction()
    {
        $client = static::createClient();
        $client->request('GET', '/path/c_0_0_200_200/'.BaseTest::JPG_TEST_IMAGE);

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertFalse($client->getResponse()->isEmpty());
    }

    public function testPathActionNotFound()
    {
        $client = static::createClient();
        $client->request('GET', '/path/c_0_0_200_200/Rovinj-Croatia-nonExist.jpg');

        $this->assertEquals(404, $client->getResponse()->getStatusCode());
    }
}
