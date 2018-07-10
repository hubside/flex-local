<?php

namespace Hubside\Composer\Flex;

use Composer\Composer;
use Composer\DependencyResolver\Operation\InstallOperation;
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
     * @var array
     */
    protected $operations = [];

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
            PackageEvents::POST_PACKAGE_UPDATE => 'record',
            PackageEvents::POST_PACKAGE_UNINSTALL => 'record',
            //ScriptEvents::POST_CREATE_PROJECT_CMD => 'configureProject',
            ScriptEvents::POST_INSTALL_CMD => 'install',
            ScriptEvents::POST_UPDATE_CMD => 'update',
            //PluginEvents::PRE_FILE_DOWNLOAD => 'onFileDownload',
            //'auto-scripts' => 'executeAutoScripts',
        ];
    }

    public function record(PackageEvent $event)
    {
        $operation = $event->getOperation();

        if ($operation instanceof InstallOperation && in_array($packageName = $operation->getPackage()->getName(), ['symfony/framework-bundle', 'symfony/flex'])) {

            var_dump($packageName);
            return;

            if ('symfony/flex' === $packageName) {
                array_unshift($this->operations, $operation);
            } else {
                if ($this->operations && $this->operations[0] instanceof InstallOperation && 'symfony/flex' === $this->operations[0]->getPackage()->getName()) {
                    // framework-bundle should be *after* flex
                    $flexOperation = $this->operations[0];
                    unset($this->operations[0]);
                    array_unshift($this->operations, $operation);
                    array_unshift($this->operations, $flexOperation);
                } else {
                    array_unshift($this->operations, $operation);
                }
            }
        } else {
            $this->operations[] = $operation;
        }
    }

    public function install(Event $event)
    {
        $this->update($event);
    }

    public function update(Event $event, $operations = [])
    {
        // @todo
    }
}

