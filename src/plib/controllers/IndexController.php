<?php

use \PleskExt\Welcome\Form\Config;

class IndexController extends pm_Controller_Action
{
    public function indexAction()
    {

    }

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
