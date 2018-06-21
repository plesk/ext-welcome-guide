<?php

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

        $this->view->form = $form;
    }

    /**
     * Trigger action for installation of a transmitted extension
     */
    public function installAction()
    {
        if (!empty($_GET['extensionId'])) {
            $extension = htmlspecialchars($_GET['extensionId']);
            $result = (new Extension())->installExtension($extension);

            if (is_string($result)) {
                $this->_status->addMessage('warning', $this->lmsg('message_error_install', [
                    'error' => $result
                ]));
            }
        }

        $this->redirect(Helper::getReturnUrl());
    }
}
