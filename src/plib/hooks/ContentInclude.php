<?php
// Copyright 1999-2017. Plesk International GmbH. All rights reserved.

class Modules_Welcome_ContentInclude extends pm_Hook_ContentInclude
{

    public static function getWhiteListPages()
    {
        $white_list = array(
            '/admin/',
            '/admin/home?context=home',
            '/smb/',
            '/smb/web/view',
        );

        return $white_list;
    }
    public function getBodyContent(){
        if (pm_Session::getClient()->isAdmin()) {
            $status = pm_Settings::get('active', 1);
        $isHomePage = true;
        $page_loaded = $_SERVER['REQUEST_URI'];
        $white_list = $this->getWhiteListPages();
        if(in_array($page_loaded, $white_list)){
            $body = '<div> <div id="ext-welcome-app"></div>
            </div>';
            return $body;
        }else{
            return "";
        }

    }
}
    public function getJsOnReadyContent(){
        $page_loaded = $_SERVER['REQUEST_URI'];
        $white_list = $this->getWhiteListPages();
        if(in_array($page_loaded, $white_list)){
        return '
        require(["'.pm_Context::getBaseUrl().'bundle.js"], function (render) {
            render(document.getElementById("ext-'.pm_Context::getModuleId().'-app"), '. json_encode([
                'locale' => \pm_Locale::getCode(),
            ]) .');
        });


    var extbox = document.getElementById("ext-welcome-app");
        var body = document.getElementById("content-body");
        body.insertBefore(extbox, body.firstChild);';
    }
    return "";}



}
