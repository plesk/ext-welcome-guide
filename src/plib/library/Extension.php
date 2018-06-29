<?php
// Copyright 1999-2018. Plesk International GmbH. All rights reserved.

namespace PleskExt\Welcome;

class Extension
{
    const EXTENSIONS_FILE = '/usr/local/psa/var/modules/welcome/extensions.json';

    /**
     * @var \pm_ServerFileManager
     */
    private $fileManager;

    /**
     * @var $extensionId
     */
    private $extensionId;

    public function __construct($extensionId)
    {
        $this->fileManager = new \pm_ServerFileManager();
        $this->extensionId = $extensionId;
    }

    /**
     * Gets the name of the extension ID
     *
     * @return bool|string
     */
    public function getName()
    {
        return $this->getExtensionProperty($this->extensionId, 'name');
    }

    /**
     * Gets the UUID of the extension ID
     *
     * @return bool|string
     */
    public function getUuid()
    {
        return $this->getExtensionProperty($this->extensionId, 'uuid');
    }

    /**
     * Gets a specific property of the the requested extension ID
     *
     * @param $extensionId
     * @param $extensionProperty
     *
     * @return bool
     */
    private function getExtensionProperty($extensionId, $extensionProperty)
    {
        $extensionsData = $this->getExtensionFileData();

        if (!empty($extensionsData[$extensionId][$extensionProperty])) {
            return $extensionsData[$extensionId][$extensionProperty];
        }

        return false;
    }

    /**
     * Gets the extension file data from the JSON file
     *
     * @return array|mixed
     */
    private function getExtensionFileData()
    {
        $extenionsData = [];

        if ($this->fileManager->fileExists(self::EXTENSIONS_FILE)) {
            $extenionsDataJson = $this->fileManager->fileGetContents(self::EXTENSIONS_FILE);

            if (!empty($extenionsDataJson)) {
                $extenionsData = json_decode($extenionsDataJson, true);
            }
        }

        return $extenionsData;
    }

    /**
     * Checks whether extension is installed
     *
     * @return bool
     */
    public function isInstalled()
    {
        return file_exists(dirname(\pm_Context::getPlibDir()) . '/' . $this->extensionId);
    }

    /**
     * Creates open link for selected extension
     *
     * @return string
     */
    public function createOpenLink()
    {
        return '/modules/' . $this->extensionId;
    }

    /**
     * Creates install link for selected extension
     *
     * @return string
     */
    public function createInstallLink()
    {
        return \pm_Context::getActionUrl('index', 'install') . '?extensionId=' . $this->extensionId;
    }

    /**
     * Installs an extension from the catalog whitelist
     *
     * @return bool|string
     * @throws \pm_Exception
     */
    public function installExtension()
    {
        try {
            \pm_ApiCli::call('extension', ['--install', $this->extensionId]);
        }
        catch (\pm_Exception_ResultException $e) {
            return $e->getMessage();
        }

        (new Statistics())->increaseExtensionsInstalledValue();

        return true;
    }
}
