<?php

namespace Tests\Core\Processor;

use Tests\Core\BaseTest;

/**
 * Class ExtractProcessorTest
 * @package Tests\Core\Processor
 */
class ExtractProcessorTest extends BaseTest
{

    public function testExecuteSuccess()
    {
        $image = $this->imageHandler->processImage(
            'e_1,p1x_100,p1y_100,p2x_300,p2y_300,o_jpg,rf_1',
            parent::EXTRACT_TEST_IMAGE
        );
        $image1 = new \Imagick($image->getPath());
        $image2 = new \Imagick(parent::EXTRACT_TEST_IMAGE_RESULT);
        $result = $image1->compareImages($image2, \Imagick::METRIC_MEANSQUAREERROR);
        $this->generatedImage[] = $image;
        $this->assertFileExists($image->getPath());
        $this->assertEquals(0, $result[1]);
    }
}
