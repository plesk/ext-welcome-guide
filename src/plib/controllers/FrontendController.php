<?php
// Copyright 1999-2020. Plesk International GmbH. All rights reserved.

use PleskExt\Welcome\Config as ConfigClass;
use PleskExt\Welcome\Helper;
use PleskExt\Welcome\Progress;
use PleskExt\Welcome\Statistics;

class FrontendController extends pm_Controller_Action
{
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
        } else {
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

    /**
     * Action to register the action button clicks
     */
    public function clickAction()
    {
        $groupId = (int)$this->getParam('group', 0);
        $stepId = (int)$this->getParam('step', 0);

        (new Statistics())->increaseButtonClickCount($groupId, $stepId);

        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender(true);

        $this->getResponse()->setBody('success');
    }
}
