<?php

namespace PleskExt\Welcome\Form;

use PleskExt\Welcome\Config as ConfigClass;

class Config extends \pm_Form_Simple
{
    public function init()
    {
        parent::init();

        $config = new ConfigClass;

        $this->setEnctype('multipart/form-data');

        $this->addElement('textarea', 'json', [
            'label'    => $this->lmsg('index.config.label.json'),
            'value'    => $config->getJsonFromSession(),
            'required' => true,
            'style'    => 'width: 90%; margin-bottom: 40px;',
            'rows'     => 33,
        ]);

        $this->addElement('description', 'fileUpoadDescription', [
            'description' => $this->lmsg('index.config.label.upload.description'),
            'escape'      => false,
        ]);

        $this->addElement('file', 'fileUpload', [
            'label'       => $this->lmsg('index.config.label.upload'),
            'description' => '*.json',
            'validators'  => [
                ['Extension', true, ['json']],
            ],
        ]);

        $this->addControlButtons(['cancelLink' => \pm_Context::getModulesListUrl()]);
    }

    public function process()
    {
        $this->fileUpload->receive();

        $file = $this->fileUpload->getFileName();

        if (is_string($file)) {
            $json = file_get_contents($file);
        } else {
            $json = $this->getValue('json');
        }

        $config = new ConfigClass;

        $config->setJsonInSession($json);
        $config->save($json);
    }
}
