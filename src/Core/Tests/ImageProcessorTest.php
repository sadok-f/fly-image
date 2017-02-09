<?php

namespace Core\Tests;

use Core\Entity\Image;
use Core\Service\ImageProcessor;

class ImageProcessorTest extends BaseTest
{

    /**
     */
    public function testProcessPNG()
    {
        /** @var ImageProcessor $processor */
        $processor = $this->app['image.processor'];
        $this->image = $processor->process(parent::OPTION_URL.',o_png', parent::PNG_TEST_IMAGE);
        $this->assertFileExists($this->image->getNewFilePath());
        $this->assertEquals('image/png', $this->getFileMemeType($this->image->getNewFilePath()));
    }

    /**
     */
    public function testProcessWebP()
    {
        /** @var ImageProcessor $processor */
        $processor = $this->app['image.processor'];
        $this->image = $processor->process(parent::OPTION_URL.',o_webp', parent::PNG_TEST_IMAGE);
        $this->assertFileExists($this->image->getNewFilePath());
        $this->assertEquals('image/webp', $this->getFileMemeType($this->image->getNewFilePath()));
    }

    /**
     */
    public function testProcessJpgFromPng()
    {
        /** @var ImageProcessor $processor */
        $processor = $this->app['image.processor'];
        $this->image = $processor->process(parent::OPTION_URL.',o_jpg', parent::PNG_TEST_IMAGE);
        $this->assertFileExists($this->image->getNewFilePath());
        $this->assertEquals('image/jpeg', $this->getFileMemeType($this->image->getNewFilePath()));
    }

    /**
     */
    public function testProcessJpg()
    {
        /** @var ImageProcessor $processor */
        $processor = $this->app['image.processor'];
        $this->image = $processor->process(parent::OPTION_URL, parent::JPG_TEST_IMAGE);
        $this->assertFileExists($this->image->getNewFilePath());
    }

    /**
     * @param $filePath
     * @return mixed
     */
    protected function getFileMemeType($filePath)
    {
        return finfo_file(finfo_open(FILEINFO_MIME_TYPE), $filePath);
    }
}
