<?php

namespace Hubside\Composer\Flex;

use Composer\Composer;
use Composer\IO\IOInterface;
use Composer\Plugin\PluginInterface;
use Composer\Script\Event;
use Composer\Script\ScriptEvents;

class LocalInstallerPlugin implements PluginInterface
{
    public function activate(Composer $composer, IOInterface $io)
    {
        //$installer = new TemplateInstaller($io, $composer);
        //$composer->getInstallationManager()->addInstaller($installer);
    }

    /**
     * Returns an array of event names this subscriber wants to listen to.
     */
    public static function getSubscribedEvents()
    {
        return [
            ScriptEvents::POST_INSTALL_CMD => "processPackages",
            ScriptEvents::POST_UPDATE_CMD => "processPackages",
        ];
    }

    /**
     * @param Event $event
     * @throws \Exception
     */
    public function processPackages(Event $event)
    {
        $composer = $event->getComposer();
        $installationManager = $composer->getInstallationManager();
        $repositoryManager = $composer->getRepositoryManager();
        $localRepository = $repositoryManager->getLocalRepository();
    }
}

