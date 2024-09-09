<?php

namespace Tests\Core\StorageProvider;

use League\Flysystem\UnableToCheckFileExistence;
use Core\Exception\MissingParamsException;
use Core\Handler\ImageHandler;
use Core\StorageProvider\S3StorageProvider;
use Tests\Core\BaseTest;

/**
 * Class S3StorageProviderTest
 */
class S3StorageProviderTest extends BaseTest
{
    /**
     *
     */
    public function testUploadActionWithS3StorageS3Exception()
    {
        $this->expectException(UnableToCheckFileExistence::class);

        unset($this->app['flysystems']);
        unset($this->app['image.handler']);
        $this->app['params']->addParameter(
            'aws_s3',
            [
                'access_id' => 'xxxxx',
                'secret_key' => 'xxxxx',
                'region' => 'xxxxx',
                'bucket_name' => 'xxxxx',
            ]
        );

        $this->app->register(new S3StorageProvider());
        /** Core Manager Service */
        $this->imageHandler =
            new ImageHandler(
                $this->app['flysystems']['storage_handler'],
                $this->app['params']
            );
        $this->imageHandler->processImage(parent::OPTION_URL . ',o_webp', parent::PNG_TEST_IMAGE);
    }

    /**
     *
     */
    public function testUploadActionWithS3()
    {
        unset($this->app['flysystems']);
        unset($this->app['image.handler']);
        $this->app['params']->addParameter(
            'aws_s3',
            [
                'access_id' => 'xxxxx',
                'secret_key' => 'xxxxx',
                'region' => 'xxxxx',
                'bucket_name' => 'xxxxx',
            ]
        );

        $this->app->register(new S3StorageProvider());
        $this->assertStringMatchesFormat(
            'https://%s.s3.%s.amazonaws.com/%s', 
            $this->app['flysystems']['file_path_resolver']
        );
    }

    /**
     *
     */
    public function testUploadActionWithS3StorageException()
    {
        $this->expectException(MissingParamsException::class);
        $this->app['params']->addParameter(
            'aws_s3',
            [
                'access_id' => 'xxxxx',
                'secret_key' => '',
                'region' => '',
                'bucket_name' => '',
            ]
        );
        $this->app->register(new S3StorageProvider());
    }
}
