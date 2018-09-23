<?php

namespace Tests\Core\Entity\Image;

use Core\Entity\Image\InputImage;
use Core\Entity\Image\OutputImage;
use Core\Entity\OptionsBag;
use Tests\Core\BaseTest;

/**
 * @backupGlobals disabled
 */
class OutputImageTest extends BaseTest
{
    /**
     * Test parseOptions Method
     */
    public function testParseOptions()
    {
        /**
         * This array needs to be updated every time we
         * add or remove a URL option in the config/parametesr.yml file.
         */
        $expectedParseArray = [
            'mozjpeg' => 1,
            'quality' => 90,
            'output' => 'auto',
            'unsharp' => '0.25x0.25+8+0.065',
            'width' => '200',
            'height' => '100',
            'face-crop' => 0,
            'face-crop-position' => 0,
            'face-blur' => '1',
            'crop' => '1',
            'background' => '#999999',
            'strip' => 1,
            'resize' => '1',
            'gravity' => 'Center',
            'filter' => 'Lanczos',
            'rotate' => '-45',
            'scale' => '50',
            'sampling-factor' => '1x1',
            'refresh' => '1',
            'extent' => '100x80',
            'preserve-aspect-ratio' => 1,
            'preserve-natural-size' => 1,
            'webp-lossless' => 0,
            'gif-frame' => 0,
            'extract' => null,
            'extract-top-x' => null,
            'extract-top-y' => null,
            'extract-bottom-x' => null,
            'extract-bottom-y' => null,
        ];
        $optionsBag = new OptionsBag($this->imageHandler->appParameters(), self::OPTION_URL);
        $inputImage = new InputImage($optionsBag, self::JPG_TEST_IMAGE);

        $this->assertEquals($inputImage->optionsBag()->asArray(), $expectedParseArray);
    }

    /**
     * Test SaveToTemporaryFile
     */
    public function testSaveToTemporaryFile()
    {
        $optionsBag = new OptionsBag($this->imageHandler->appParameters(), self::OPTION_URL);
        $inputImage = new InputImage($optionsBag, self::JPG_TEST_IMAGE);
        $image = new OutputImage($inputImage);
        $this->generatedImage[] = $image;

        $this->assertFileExists($image->getInputImage()->sourcePath());
    }

    /**
     * Test GenerateFilesName
     */
    public function testGenerateFilesName()
    {
        $optionsBag = new OptionsBag($this->imageHandler->appParameters(), self::OPTION_URL);
        $inputImage = new InputImage($optionsBag, self::JPG_TEST_IMAGE);
        $image = new OutputImage($inputImage);

        $optionsBag2 = new OptionsBag($this->imageHandler->appParameters(), self::OPTION_URL);
        $inputImage2 = new InputImage($optionsBag2, self::JPG_TEST_IMAGE);
        $image2 = new OutputImage($inputImage2);

        $this->generatedImage[] = $image2;
        $this->generatedImage[] = $image;

        $this->assertEquals($image2->getOutputName(), $image->getOutputName());
        $this->assertNotEquals($image2->getPath(), $image->getPath());
    }

    /**
     * Test ExtractByKey
     */
    public function testExtractByKey()
    {
        $optionsBag = new OptionsBag($this->imageHandler->appParameters(), self::OPTION_URL);
        $inputImage = new InputImage($optionsBag, self::JPG_TEST_IMAGE);
        $image = new OutputImage($inputImage);

        $image->extractKey('width');
        $this->generatedImage[] = $image;
        $this->assertFalse(array_key_exists('width', $image->getInputImage()->optionsBag()->asArray()));
    }
}
