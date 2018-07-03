<?php
// Copyright 1999-2018. Plesk International GmbH. All rights reserved.

namespace PleskExt\Welcome;

class ExtensionApi
{
    const EXTENSIONS_FILE = 'extensions.json';
    const EXTENSIONS_FILE_DEFAULT = 'default/extensions.json';
    const EXTENSIONS_API = 'https://ext.plesk.com/api/v4/packages';

    /**
     * Creates the extension JSON file from the API or from the default file
     */
    public function createExtensionFile()
    {
        $extensionsDataApi = $this->getExtensionsInformationApiCall();

        if (!empty($extensionsDataApi)) {
            $extensionsDataApiProcessed = $this->prepareExtensionsData($extensionsDataApi);

            if ($this->saveExtensionsData($extensionsDataApiProcessed)) {
                return;
            }
        }

        $this->createExtensionFileFromDefault();
    }

    /**
     * Gets the extensions data from the API call
     *
     * @return bool|mixed|string
     */
    private function getExtensionsInformationApiCall()
    {
        $client = new \Zend_Http_Client(self::EXTENSIONS_API);

        try {
            $extensionsApiResult = $client->request(\Zend_Http_Client::GET);
        }
        catch (\Exception $e) {
            return false;
        }

        $extensionsApiBody = json_decode($extensionsApiResult->getBody());

        if (!$extensionsApiResult->isError()) {
            return $extensionsApiBody;
        }

        return false;
    }

    /**
     * Prepares the extensions data from the API call
     *
     * @param $extensionsDataApi
     *
     * @return string
     */
    private function prepareExtensionsData($extensionsDataApi)
    {
        $extensionsDataProcessed = [];

        foreach ($extensionsDataApi as $extensionData) {
            $extensionsDataProcessed[$extensionData->code] = [
                'uuid' => $extensionData->uuid,
                'name' => $extensionData->name,
            ];
        }

        return json_encode($extensionsDataProcessed);
    }

    /**
     * Saves the extensions data that was retrieved from the API call
     *
     * @param $extensionsDataApiProcessed
     *
     * @return bool
     */
    private function saveExtensionsData($extensionsDataApiProcessed)
    {
        if (empty($extensionsDataApiProcessed)) {
            return false;
        }

        file_put_contents(\pm_Context::getVarDir() . self::EXTENSIONS_FILE, $extensionsDataApiProcessed);
    }

    /**
     * Creates the extensions file from the provided default file
     */
    private function createExtensionFileFromDefault()
    {
        if (file_exists(\pm_Context::getVarDir() . self::EXTENSIONS_FILE)) {
            unlink(\pm_Context::getVarDir() . self::EXTENSIONS_FILE);
        }

        copy(\pm_Context::getVarDir() . self::EXTENSIONS_FILE_DEFAULT, \pm_Context::getVarDir() . self::EXTENSIONS_FILE);
    }
}
