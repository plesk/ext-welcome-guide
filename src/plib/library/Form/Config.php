<?php
// Copyright 1999-2020. Plesk International GmbH. All rights reserved.

namespace PleskExt\Welcome\Form;

use PleskExt\Welcome\Config as ConfigClass;

class Config extends \pm_Form_Simple
{
    public function init()
    {
        parent::init();

        $config = new ConfigClass;

        $this->setEnctype('multipart/form-data');

        $this->addElement(
            'textarea',
            'json',
            [
                'label'    => $this->lmsg('index.config.label.json'),
                'value'    => $config->getJsonFromSession(),
                'required' => true,
                'style'    => 'width: 90%; margin-bottom: 40px;',
                'rows'     => 33,
            ]
        );

        $this->addElement(
            'description',
            'fileUpoadDescription',
            [
                'description' => $this->lmsg('index.config.label.upload.description'),
                'escape'      => false,
            ]
        );

        $this->addElement(
            'hidden',
            'MAX_FILE_SIZE',
            [
                'value' => '2097152',
            ]
        );

        $this->addElement(
            'file',
            'fileUpload',
            [
                'label'       => $this->lmsg('index.config.label.upload'),
                'description' => '*.json, <= 2MB',
                'accept'      => '.json',
            ]
        );

        $this->addControlButtons(['cancelLink' => \pm_Context::getModulesListUrl()]);
    }

    /**
     * @throws \Zend_Db_Table_Exception
     * @throws \Zend_Db_Table_Row_Exception
     * @throws \pm_Exception_InvalidArgumentException
     */
    public function process()
    {
        $this->fileUpload->receive();

        $file = $this->fileUpload->getFileName();
        $config = new ConfigClass;
        $isUploadedFile = false;

        if (is_string($file)) {
            $isUploadedFile = true;
            $json = file_get_contents($file);
        } else {
            $json = $this->getValue('json');

            $config->setJsonInSession($json);
        }

        $config->save($json, $isUploadedFile);
    }
}
