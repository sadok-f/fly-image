<?php

namespace Tests\Core\Service;

use Core\Entity\Image\OutputImage;
use Tests\Core\BaseTest;

class ImageHandlerTest extends BaseTest
{
    /**
     */
    public function testProcessPNG()
    {
        $image = $this->ImageHandler->processImage(parent::CROP_OPTION_URL, parent::PNG_TEST_IMAGE);
        $this->generatedImage[] = $image;
        $this->assertFileExists($image->getPath());
        $this->assertEquals(OutputImage::PNG_MIME_TYPE, $this->getFileMimeType($image->getPath()));
    }

    /**
     */
    public function testProcessWebpFromPng()
    {
        $image = $this->ImageHandler->processImage(parent::OPTION_URL.',o_webp', parent::PNG_TEST_IMAGE);
        $this->generatedImage[] = $image;
        $this->assertFileExists($image->getPath());
        $this->assertEquals(OutputImage::WEBP_MIME_TYPE, $this->getFileMimeType($image->getPath()));
    }

    /**
     */
    public function testProcessJpgFromPng()
    {
        $image = $this->ImageHandler->processImage(parent::OPTION_URL.',o_jpg', parent::PNG_TEST_IMAGE);
        $this->generatedImage[] = $image;
        $this->assertFileExists($image->getPath());
        $this->assertEquals(OutputImage::JPEG_MIME_TYPE, $this->getFileMimeType($image->getPath()));
    }

    /**
     */
    public function testProcessGifFromPng()
    {
        $image = $this->ImageHandler->processImage(parent::OPTION_URL.',o_gif', parent::PNG_TEST_IMAGE);
        $this->generatedImage[] = $image;
        $this->assertFileExists($image->getPath());
        $this->assertEquals(OutputImage::GIF_MIME_TYPE, $this->getFileMimeType($image->getPath()));
    }

    /**
     */
    public function testProcessJpg()
    {
        $image = $this->ImageHandler->processImage(parent::OPTION_URL, parent::JPG_TEST_IMAGE);
        $this->generatedImage[] = $image;
        $this->assertFileExists($image->getPath());
    }

    /**
     */
    public function testProcessGif()
    {
        $image = $this->ImageHandler->processImage(parent::GIF_OPTION_URL, parent::GIF_TEST_IMAGE);
        $this->generatedImage[] = $image;
        $this->assertFileExists($image->getPath());
        $this->assertEquals(OutputImage::GIF_MIME_TYPE, $this->getFileMimeType($image->getPath()));
    }

    /**
     */
    public function testProcessPngFromGif()
    {
        $image = $this->ImageHandler->processImage(parent::GIF_OPTION_URL.',o_png', parent::GIF_TEST_IMAGE);
        $this->generatedImage[] = $image;
        $this->assertFileExists($image->getPath());
        $this->assertEquals(OutputImage::PNG_MIME_TYPE, $this->getFileMimeType($image->getPath()));
    }

    /**
     */
    public function testProcessJpgFromGif()
    {
        $image = $this->ImageHandler->processImage(parent::GIF_OPTION_URL.',o_jpg', parent::GIF_TEST_IMAGE);
        $this->generatedImage[] = $image;
        $this->assertFileExists($image->getPath());
        $this->assertEquals(OutputImage::JPEG_MIME_TYPE, $this->getFileMimeType($image->getPath()));
    }

    /**
     */
    public function testProcessWebpFromGif()
    {
        $image = $this->ImageHandler->processImage(parent::GIF_OPTION_URL.',o_webp', parent::GIF_TEST_IMAGE);
        $this->generatedImage[] = $image;
        $this->assertFileExists($image->getPath());
        $this->assertEquals(OutputImage::WEBP_MIME_TYPE, $this->getFileMimeType($image->getPath()));
    }

    /**
     * @param $filePath
     *
     * @return mixed
     */
    protected function getFileMimeType($filePath)
    {
        return finfo_file(finfo_open(FILEINFO_MIME_TYPE), $filePath);
    }
}
