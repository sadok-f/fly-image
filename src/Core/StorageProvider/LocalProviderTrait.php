<?php

namespace Core\StorageProvider;

use League\Flysystem\MountManager;
use Pimple\Container;
use League\Flysystem\Filesystem;

trait LocalProviderTrait
{
    /**
     * Register this service provider with the Application.
     *
     * @param Container $app Application.
     *
     * @return void
     */
    protected function registerFlysystems(Container $app)
    {
        $app['flysystem.filesystems'] = [];

        $app['flysystems'] = function (Container $app) {
            $flysystems = new Container();
            foreach ($app['flysystem.filesystems'] as $alias => $parameters) {
                $flysystems[$alias] = $this->buildFilesystem($app, $parameters);
            }
            return $flysystems;
        };

        $app['flysystem.mount_manager'] = function (Container $app) {
            $mountManager = new MountManager();
            foreach ($app['flysystem.filesystems'] as $alias => $parameters) {
                $mountManager->mountFilesystem($alias, $app['flysystems'][$alias]);
            }
            return $mountManager;
        };
    }

    /**
     * Instantiate an adapter and wrap it in a filesystem.
     *
     * @param array $parameters Array containing the adapter classname and arguments that need to be passed into it.
     *
     * @return Filesystem
     */
    protected function buildFilesystem(Container $app, array $parameters)
    {
        $adapter = new \ReflectionClass($parameters['adapter']);
        $filesystem = new Filesystem($adapter->newInstanceArgs($parameters['args']), $this->getConfig($parameters));
        return $filesystem;
    }

    protected function getConfig(array $parameters)
    {
        if (isset($parameters['config'])) {
            return $parameters['config'];
        }

        return [];
    }
}
