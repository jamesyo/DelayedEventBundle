<?php

namespace Tests\Vivait\DelayedEventBundle\app;

use Doctrine\Bundle\DoctrineBundle\DoctrineBundle;
use Doctrine\Bundle\DoctrineCacheBundle\DoctrineCacheBundle;
use Leezy\PheanstalkBundle\LeezyPheanstalkBundle;
use Symfony\Bundle\FrameworkBundle\FrameworkBundle;
use Symfony\Component\HttpKernel\Kernel;
use Symfony\Component\Config\Loader\LoaderInterface;
use Vivait\DelayedEventBundle\VivaitDelayedEventBundle;

class AppKernel extends Kernel
{
    public function registerBundles()
    {
        return array(
            new FrameworkBundle(),
            new DoctrineBundle(),
            new VivaitDelayedEventBundle(),
            new DoctrineCacheBundle()
        );
    }

    public function registerContainerConfiguration(LoaderInterface $loader)
    {
        $loader->load(__DIR__.'/config.yml');
    }

    /**
     * @return string
     */
    public function getCacheDir()
    {
        return sys_get_temp_dir() . '/VivaitDelayedExtensionBundle/cache';
    }
    /**
     * @return string
     */
    public function getLogDir()
    {
        return sys_get_temp_dir() . '/VivaitDelayedExtensionBundle/logs';
    }

}