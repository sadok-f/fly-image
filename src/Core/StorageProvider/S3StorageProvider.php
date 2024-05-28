<?php

namespace Core\StorageProvider;

use Aws\S3\S3Client;
use Core\Exception\MissingParamsException;
use Pimple\Container;
use Pimple\ServiceProviderInterface;

/**
 * Storage class to manage Storage provider from FlySystem
 *
 * Class StorageProvider
 * @package Core\Provider
 */
class S3StorageProvider implements ServiceProviderInterface
{
    /**
     * @param Container $app
     *
     * @throws MissingParamsException
     */
    public function register(Container $app)
    {
        $s3Params = $app['params']->parameterByKey('aws_s3');
        if (in_array("", $s3Params)) {
            throw new MissingParamsException("One of AWS S3 parameters in empty ! ");
        }

        $this->registerS3ServiceProvider($app, $s3Params);
        $app['flysystems']['file_path_resolver'] = function () use ($s3Params) {
            return isset($s3Params['endpoint'])
                ? sprintf($s3Params['endpoint'] . '/%s/', $s3Params['endpoint'], $s3Params['bucket_name']) . '%s'
                : sprintf('https://s3.%s.amazonaws.com/%s/', $s3Params['region'], $s3Params['bucket_name']) . '%s';
        };
    }

    /**
     * @param Container $app
     * @param array     $s3Params
     *
     * @return Container
     */
    protected function registerS3ServiceProvider(Container $app, array $s3Params): Container
    {
        $clientParams = [
            'credentials' => [
                'key' => $s3Params['access_id'],
                'secret' => $s3Params['secret_key'],
            ],
            'region' => $s3Params['region'],
            'version' => 'latest',
            'bucket_endpoint' => $s3Params['bucket_endpoint'] ?? true,
            'use_path_style_endpoint' => $s3Params['use_path_style_endpoint'] ?? false,
        ];

        // Support for third party S3 compatible services
        if (isset($s3Params['endpoint'])) {
            $clientParams['endpoint'] = $s3Params['endpoint'];
        }

        $app->register(
            new FlysystemServiceProvider(),
            [
                'flysystem.filesystems' => [
                    'storage_handler' => [
                        'adapter' => 'League\Flysystem\AwsS3v3\AwsS3Adapter',
                        'args' => [
                            new S3Client($clientParams),
                            $s3Params['bucket_name'],
                        ],
                    ],
                ],
            ]
        );

        return $app;
    }
}
