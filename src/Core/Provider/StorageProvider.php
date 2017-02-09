<?php

namespace Core\Provider;

use Aws\S3\S3Client;
use Core\Exception\MissingParamsException;
use Pimple\Container;
use Pimple\ServiceProviderInterface;
use WyriHaximus\SliFly\FlysystemServiceProvider;

/**
 * Storage class to manage Storage provider from FlySystem
 *
 * Class StorageProvider
 * @package Core\Provider
 */
class StorageProvider implements ServiceProviderInterface
{
    /**
     * Registers services on the given app.
     * This method should only be used to configure services and parameters.
     * It should not get services.
     *
     * @param Container $app Container
     */
    public function register(Container $app)
    {
        switch ($app['params']['storage_system']) {
            case 's3':
                $this->registerStorageSystemS3($app);
                break;
            case 'local':
            default:
                $this->registerStorageSystemLocal($app);
                break;
        }
    }

    /**
     * @param Container $app
     */
    protected function registerStorageSystemLocal(Container $app)
    {
        $app->register(
            new FlysystemServiceProvider(),
            [
                'flysystem.filesystems' => [
                    'upload_dir' => [
                        'adapter' => 'League\Flysystem\Adapter\Local',
                        'args' => [UPLOAD_DIR],
                    ],
                ],
            ]
        );

        $app['flysystems']['file_path_resolver'] = function () use ($app) {
            $hostname = getenv('HOSTNAME_URL');
            if (empty($hostname)) {
                $schema = $app['request_context']->getScheme();
                $host = $app['request_context']->getHost();
                $port = $app['request_context']->getHttpPort();
                $hostname = $schema.'://'.$host.($port == '80' ? '' : ':'.$port);
            }

            return $hostname.'/'.UPLOAD_WEB_DIR.'%s';
        };
    }

    /**
     * @param Container $app
     * @throws \Exception
     */
    protected function registerStorageSystemS3(Container $app)
    {
        $s3Params = $app['params']['aws_s3'];
        if (in_array("", $s3Params)) {
            throw new MissingParamsException("One of AWS S3 parameters in empty ! ");
        }
        $s3Client = new S3Client(
            [
                'credentials' => [
                    'key' => $s3Params['access_id'],
                    'secret' => $s3Params['secret_key'],
                ],
                'region' => $s3Params['region'],
                'version' => 'latest',
            ]
        );

        $app->register(
            new FlysystemServiceProvider(),
            [
                'flysystem.filesystems' => [
                    'upload_dir' => [
                        'adapter' => 'League\Flysystem\AwsS3v3\AwsS3Adapter',
                        'args' => [
                            $s3Client,
                            $s3Params['bucket_name'],
                        ],
                    ],
                ],
            ]
        );

        $app['flysystems']['file_path_resolver'] = function () use ($app, $s3Params) {
            return sprintf('https://s3.%s.amazonaws.com/%s/', $s3Params['region'], $s3Params['bucket_name']).'%s';
        };
    }
}
