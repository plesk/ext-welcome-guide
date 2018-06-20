<?php
// Copyright 1999-2018. Plesk International GmbH. All rights reserved.

namespace PleskExt\Welcome;

/**
 * Class Installer
 *
 * @package PleskExt\Welcome
 */
abstract class Installer
{
    /**
     * Creates the a default config file with the preset default if it does not exist
     *
     * @param string $presetName
     */
    public static function createDefaultConfig($presetName = 'default')
    {
        (new Config())->createConfigFromPreset($presetName);
    }

    /**
     * Creates the extensions data file which is required for some pre-defined actions
     */
    public static function createExtensionsFile()
    {
        (new ExtensionApi())->createExtensionFile();
    }
}