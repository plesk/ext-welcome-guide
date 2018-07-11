<?php
// Copyright 1999-2018. Plesk International GmbH. All rights reserved.

namespace PleskExt\Welcome;

/**
 * Class Helper
 *
 * @package PleskExt\Welcome
 */
abstract class Helper
{
    /**
     * Creates return URL from the global HTTP_REFERER variable but with validity check
     *
     * @return string
     */
    public static function getReturnUrl()
    {
        $urlGlobalGeneric = self::getGlobalUrl();

        if (!empty($_SERVER['HTTP_REFERER'])) {

            $urlGlobalReturn = $_SERVER['HTTP_REFERER'];
            $whiteList = self::getWhiteListPages();

            foreach ($whiteList as $item) {
                if ($urlGlobalGeneric . $item == $urlGlobalReturn) {
                    return $urlGlobalReturn;
                }
            }
        }

        return $urlGlobalGeneric;
    }

    /**
     * Determines global URL dependent of the OS for the return redirect
     *
     * @return string
     */
    private static function getGlobalUrl()
    {
        $serverScheme = self::getServerScheme();
        $serverHost = self::getServerHost();

        return htmlspecialchars($serverScheme . '://' . $serverHost);
    }

    /**
     * Retrieves the request scheme from the global server variable
     *
     * @return string
     */
    private static function getServerScheme()
    {
        if (!empty($_SERVER['REQUEST_SCHEME'])) {
            return $_SERVER['REQUEST_SCHEME'];
        }

        if (!empty($_SERVER['HTTPS']) AND $_SERVER['HTTPS'] != 'on') {
            return 'http';
        }

        return 'https';
    }

    /**
     * Retrieves the server host from the global server variable
     *
     * @return string
     */
    private static function getServerHost()
    {
        if (!empty($_SERVER['HTTP_HOST'])) {
            return $_SERVER['HTTP_HOST'];
        }

        return $_SERVER['SERVER_ADDR'] . ':' . $_SERVER['SERVER_PORT'];
    }

    /**
     * Returns all pages where the tutorial banner should be loaded
     *
     * @return array
     */
    public static function getWhiteListPages()
    {
        $whiteList = array(
            '/admin/',
            '/admin/home?context=home',
            '/smb/',
            '/smb/web/view',
            '/smb/web/setup',
        );

        return $whiteList;
    }

    /**
     * Checks whether at least one domain is activated in the subscription
     *
     * @return bool
     * @throws \pm_Exception
     */
    public static function checkAvailableDomains()
    {
        $xmlData = new \SimpleXMLElement('<packet><server><get><stat/></get></server></packet>');
        $xml = \pm_ApiRpc::getService()->call($xmlData->children()[0]->asXml());

        if ($xml->xpath('//objects')[0]->domains == 0) {
            return false;
        }

        return true;
    }

    /**
     * Workaround to avoid duplicate message instances when changing view - load only once each 3 seconds
     *
     * @return bool
     */
    public static function addMessage()
    {
        $executed = (int) \pm_Settings::get('executed', 0);

        if (empty($executed)) {
            return true;
        }

        if (time() - $executed >= 3) {
            return true;
        }

        return false;
    }

    /**
     * Gets the correct link to the new domain creation page
     *
     * @return string
     * @throws \pm_Exception
     */
    public static function getAdvisorData($element = 'id')
    {
        $advisorExtension = ['name' => 'Security Advisor', 'id' => 'security-advisor'];

        if (self::isPleskVersion178()) {
            $advisorExtension = ['name' => 'Advisor', 'id' => 'advisor'];
        }

        return $advisorExtension[$element];
    }

    /**
     * Checks whether Plesk version is >= 17.8.10
     *
     * @return bool
     * @throws \pm_Exception
     */
    private static function isPleskVersion178()
    {
        $pleskVersion = \pm_ProductInfo::getVersion();

        if (version_compare($pleskVersion, '17.8.10', 'ge')) {
            return true;
        }

        return false;
    }

    /**
     * Gets the correct link to the new domain creation page
     *
     * @return string
     */
    public static function getLinkNewDomain()
    {
        $pageLoaded = self::getRefererPage();

        if (stripos($pageLoaded, '/admin/') !== false) {
            return '/admin/domain/add-domain';
        }

        return '/smb/web/add-domain';
    }

    /**
     * Gets the referer page using the global HTTP_REFERER variable
     *
     * @return mixed
     */
    public static function getRefererPage()
    {
        return $_SERVER['HTTP_REFERER'];
    }
}
