<?php

namespace Tests\Core;

use Core\Entity\Image\OutputImage;
use Core\Handler\ImageHandler;
use Silex\Application;

class BaseTest extends \PHPUnit_Framework_TestCase
{
    const JPG_TEST_IMAGE = __DIR__.'/../testImages/square.jpg';
    const PNG_TEST_IMAGE = __DIR__.'/../testImages/square.png';
    const WEBP_TEST_IMAGE = __DIR__.'/../testImages/square.webp';
    const GIF_TEST_IMAGE = __DIR__.'/../testImages/animated.gif';

    const FACES_TEST_IMAGE = __DIR__.'/../testImages/faces.jpg';
    const FACES_CP0_TEST_IMAGE = __DIR__.'/../testImages/face_cp0.jpg';
    const FACES_BLUR_TEST_IMAGE = __DIR__.'/../testImages/face_fb.jpg';

    const EXTRACT_TEST_IMAGE = __DIR__.'/../testImages/extract-original.jpg';
    const EXTRACT_TEST_IMAGE_RESULT = __DIR__.'/../testImages/extract-result.jpg';

    const OPTION_URL = 'w_200,h_100,c_1,bg_#999999,rz_1,sc_50,r_-45,unsh_0.25x0.25+8+0.065,ett_100x80,fb_1,rf_1';
    const CROP_OPTION_URL = 'w_200,h_100,c_1,rf_1';
    const GIF_OPTION_URL = 'w_100,h_100,rf_1';

    /**
     * @var Application
     */
    protected $app = null;

    /**
     * @var ImageHandler
     */
    protected $ImageHandler = null;

    /**
     * @var array
     */
    protected $generatedImage = [];

    /**
     *
     */
    public function setUp()
    {
        $this->app = $this->createApplication();
        $this->ImageHandler = $this->app['image.handler'];
    }

    /**
     *
     */
    protected function tearDown()
    {
        unset($this->ImageHandler);
        unset($this->app);

        foreach ($this->generatedImage as $image) {
            if ($image instanceof OutputImage) {
                if (file_exists(UPLOAD_DIR.$image->getOutputName())) {
                    unlink(UPLOAD_DIR.$image->getOutputName());
                }
                $image->getInputImage()->removeFile();
            }
        }
    }


    /**
     * @return Application
     */
    public function createApplication()
    {
        $app = require __DIR__.'/../../app.php';
        $app['debug'] = true;
        unset($app['exception_handler']);

        return $app;
    }

    /**
     */
    public function testApplicationInstance()
    {
        $this->assertInstanceOf('Silex\Application', $this->app);
    }
}
