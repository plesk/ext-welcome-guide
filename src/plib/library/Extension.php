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

    public function __construct()
    {
        $this->fileManager = new \pm_ServerFileManager();
    }

    /**
     * Gets the name of the extension ID
     *
     * @param $extensionId
     *
     * @return bool|string
     */
    public function getName($extensionId)
    {
        return $this->getExtensionProperty($extensionId, 'name');
    }

    /**
     * Gets the UUID of the extension ID
     *
     * @param $extensionId
     *
     * @return bool|string
     */
    public function getUuid($extensionId)
    {
        return $this->getExtensionProperty($extensionId, 'uuid');
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
}
