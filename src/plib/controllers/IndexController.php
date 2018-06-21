<?php

use \PleskExt\Welcome\Form\Config;

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
            $form->process();

            $this->_status->addInfo($this->lmsg('index.config.message.success'));

            $this->_helper->json(array('redirect' => pm_Context::getBaseUrl()));
        }

        $this->view->form = $form;
    }
}
