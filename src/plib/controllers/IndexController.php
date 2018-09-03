<?php

use PleskExt\Welcome\Config as ConfigClass;
use PleskExt\Welcome\Form\Config;
use PleskExt\Welcome\Extension;
use PleskExt\Welcome\Helper;

class IndexController extends pm_Controller_Action
{
    protected $_accessLevel = 'admin';

    /**
     * Entry point of the extension which redirects all request to the config action
     */
    public function indexAction()
    {
        $this->forward('config');
    }

    /**
     * Configuration page only accessible by administrators of the server
     */
    public function configAction()
    {
        $form = new Config;
        $config = new ConfigClass;

        if ($this->getRequest()->isPost() && $form->isValid($this->getRequest()->getPost())) {
            try {
                $form->process();

                $this->_status->addInfo($this->lmsg('index.config.message.success'));
            }
            catch (\Exception $e) {
                $this->_status->addError($e->getMessage());
            }

            $this->redirect(\pm_Context::getBaseUrl());
        }

        $presetSelector = [];

        foreach ($config->getPresets() as $preset) {
            $presetSelector[] = [
                'title' => $config->getPreparedPresetName($preset),
                'class' => 'sb-preset-select',
                'link'  => "javascript:welcomeLoadPreset('{$preset}')",
            ];
        }

        $this->view->presetSelector = $presetSelector;
        $this->view->form = $form;
        $this->view->help = (new \Zend_View(['scriptPath' => \pm_Context::getPlibDir() . 'views']))->render('partials/help.phtml');

        $this->view->headLink()->appendStylesheet(pm_Context::getBaseUrl() . 'css/ext-welcome.css');
    }

    /**
     * Used for Ajax calls to get preset JSON configuration
     */
    public function presetAction()
    {
        $preset = $this->getParam('preset');
        $config = new ConfigClass;
        $json = $config->getPresetConfig($preset);

        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender(true);

        $this->getResponse()->setBody($json);
    }

    /**
     * Trigger action for installation of a transmitted extension
     */
    public function installAction()
    {
        if (!empty($_GET['extensionId'])) {
            $extension = htmlspecialchars($_GET['extensionId']);
            $result = (new Extension($extension))->installExtension();

            if (is_string($result)) {
                $this->_status->addMessage('warning', $this->lmsg('index.install.message.failure', [
                    'error' => $result,
                ]));
            }
        }

        $this->redirect(Helper::getReturnUrl());
    }
}
