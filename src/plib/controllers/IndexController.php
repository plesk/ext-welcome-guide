<?php

use PleskExt\Welcome\Config as ConfigClass;
use PleskExt\Welcome\Form\Config;
use PleskExt\Welcome\Extension;
use PleskExt\Welcome\Helper;
use PleskExt\Welcome\Progress;

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

            $this->_helper->json(array('redirect' => \pm_Context::getBaseUrl()));
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

    /**
     * Ajax callback for step checkbox click
     */
    public function progressAction()
    {
        $groupId = (int)$this->getParam('group', 0);
        $stepId = (int)$this->getParam('step', 0);
        $progress = new Progress;

        $progress->completeStep($groupId, $stepId);

        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender(true);

        $this->getResponse()->setBody('success');
    }

    /**
     * Get data via Ajax request for specific groups and steps
     */
    public function groupAction()
    {
        $groupId = (int)$this->getParam('group', 0);
        $stepId = (int)$this->getParam('step', -1);
        $config = new ConfigClass;
        $data = $config->getProcessedConfigData();
        $steps = isset($data['groups'][$groupId]) ? $data['groups'][$groupId]['steps'] : [];

        if ($stepId === -1) {
            $result = $steps;
        }
        else {
            $result = isset($steps[$stepId]) ? $steps[$stepId] : [];
        }

        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender(true);

        $this->getResponse()->setBody(json_encode($result));
    }

    /**
     * Action to disable the Welcome box for a specific user
     */
    public function disableAction()
    {
        $config = new ConfigClass;

        $config->disableExtension();

        $this->redirect(Helper::getReturnUrl());
    }

    /**
     * Action to enable the Welcome box for a specific user
     */
    public function enableAction()
    {
        $config = new ConfigClass;

        $config->enableExtension();

        $this->redirect(Helper::getReturnUrl());
    }
}
