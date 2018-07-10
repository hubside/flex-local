<?php

namespace Hubside\Composer\Flex;

use Composer\Composer;
use Composer\EventDispatcher\EventSubscriberInterface;
use Composer\Installer\PackageEvent;
use Composer\Installer\PackageEvents;
use Composer\IO\IOInterface;
use Composer\Plugin\PluginInterface;
use Composer\Script\Event;
use Composer\Script\ScriptEvents;

/**
 * Class LocalRecipe
 * @package Hubside\Composer\Flex
 */
class LocalRecipe implements PluginInterface, EventSubscriberInterface
{
    /**
     * @var bool
     */
    protected static $activated = false;

    /**
     * @param Composer $composer
     * @param IOInterface $io
     */
    public function activate(Composer $composer, IOInterface $io)
    {
        self::$activated = true;

        $io->write(__METHOD__.PHP_EOL);
    }

    /**
     * @return array
     */
    public static function getSubscribedEvents(): array
    {
        if (!self::$activated) {
            return [];
        }

        return [
            //InstallerEvents::PRE_DEPENDENCIES_SOLVING => [['populateProvidersCacheDir', PHP_INT_MAX]],
            //InstallerEvents::POST_DEPENDENCIES_SOLVING => [['populateFilesCacheDir', PHP_INT_MAX]],
            //PackageEvents::PRE_PACKAGE_INSTALL => [['populateFilesCacheDir', ~PHP_INT_MAX]],
            //PackageEvents::PRE_PACKAGE_UPDATE => [['populateFilesCacheDir', ~PHP_INT_MAX]],
            PackageEvents::POST_PACKAGE_INSTALL => 'record',
            //PackageEvents::POST_PACKAGE_UPDATE => [['record'], ['enableThanksReminder']],
            PackageEvents::POST_PACKAGE_UNINSTALL => 'record',
            //ScriptEvents::POST_CREATE_PROJECT_CMD => 'configureProject',
            //ScriptEvents::POST_INSTALL_CMD => 'install',
            //ScriptEvents::POST_UPDATE_CMD => 'update',
            //PluginEvents::PRE_FILE_DOWNLOAD => 'onFileDownload',
            //'auto-scripts' => 'executeAutoScripts',
        ];
    }

    public function record(PackageEvent $event)
    {
        var_dump($event->getName());
    }
}

