<?php

namespace Core\Tests;

use Core\Entity\Image;
use Core\Service\ImageProcessor;

class ImageProcessorTest extends BaseTest
{
    /**
     */
    public function testProcess()
    {
        $this->image = new Image(parent::OPTION_URL, parent::IMG_TEST_PATH, $this->app['params']);
        /** @var ImageProcessor $processor */
        $processor = $this->app['image.processor'];
        $processor->process($this->image);

        $this->assertFileExists($this->image->getNewFilePath());
    }
}
