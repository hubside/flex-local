<?php

namespace Hubside\Flex;

use Composer\Composer;
use Composer\IO\IOInterface;
use Composer\Plugin\PluginInterface;

class LocalInstallerPlugin implements PluginInterface
{
    public function activate(Composer $composer, IOInterface $io)
    {
        //$installer = new TemplateInstaller($io, $composer);
        //$composer->getInstallationManager()->addInstaller($installer);
    }
}

