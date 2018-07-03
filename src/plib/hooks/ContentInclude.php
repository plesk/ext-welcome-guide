<?php
// Copyright 1999-2018. Plesk International GmbH. All rights reserved.

use PleskExt\Welcome\Helper;
use PleskExt\Welcome\Config;

class Modules_Welcome_ContentInclude extends pm_Hook_ContentInclude
{
    /**
     * Adds the ID for the Welcome box
     *
     * @return string
     */
    public function getBodyContent()
    {
        if ($this->loadContentCode()) {
            return '<div id="ext-welcome-app"></div>';
        }
    }

    /**
     * Loads the required JavaScript code to the head section
     *
     * @return string
     */
    public function getJsContent()
    {
        if ($this->loadContentCode()) {
            return 'require(["' . \pm_Context::getBaseUrl() . 'js/main.js"], function (render) {
                        render(document.getElementById("ext-welcome-app"), ' . json_encode([
                    'locale' => \pm_Locale::getSection('welcomebox'),
                    'data'   => (new Config())->getProcessedConfigData(),
                ]) . ');
                });';
        }
    }

    /**
     * Loads the required JavaScript code to include the Welcome box after the DOMReady event was fired
     *
     * @return string
     */
    public function getJsOnReadyContent()
    {
        if ($this->loadContentCode()) {
            return 'var extensionBox = document.getElementById("ext-welcome-app");
                var body = document.getElementById("content-body");
                body.insertBefore(extensionBox, body.firstChild);';
        }
    }

    /**
     * Checks whether the content code should be loaded at all in this hook
     *
     * @return bool
     */
    private function loadContentCode()
    {
        if (!\pm_Session::getClient()->isAdmin()) {
            return false;
        }

        $pageLoaded = $_SERVER['REQUEST_URI'];
        $whiteList = Helper::getWhiteListPages();

        if (!in_array($pageLoaded, $whiteList)) {

            return false;
        }

        if (!(new Config())->isExtensionEnabled()) {
            return false;
        }

        return true;
    }
}
