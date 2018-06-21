<?php
// Copyright 1999-2017. Plesk International GmbH. All rights reserved.

use PleskExt\Welcome\Helper;

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
     * Loads the required JavaScript code to the head section after the DOMReady event was fired
     *
     * @return string
     */
    public function getJsOnReadyContent()
    {
        if ($this->loadContentCode()) {
            return 'require(["' . pm_Context::getBaseUrl() . 'bundle.js"], function (render) {
                        render(document.getElementById("ext-welcome-app"), ' . json_encode([
                    'locale' => \pm_Locale::getCode(),
                ]) . ');
                });
    
                var extensionBox = document.getElementById("ext-welcome-app");
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
        if (pm_Session::getClient()->isAdmin()) {
            $pageLoaded = $_SERVER['REQUEST_URI'];
            $whiteList = Helper::getWhiteListPages();

            if (in_array($pageLoaded, $whiteList)) {
                return true;
            }
        }

        return false;
    }
}
