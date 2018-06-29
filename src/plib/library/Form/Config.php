<?php

namespace PleskExt\Welcome\Form;

use PleskExt\Welcome\Config as ConfigClass;

class Config extends \pm_Form_Simple
{
    public function init()
    {
        parent::init();

        $config = new ConfigClass;

        $this->addElement('textarea', 'json', [
            'label' => $this->lmsg('index.config.label.json'),
            'value' => $config->getJsonFromSession(),
            'required' => true,
            'style' => 'width: 90%;',
            'rows' => 33,
        ]);

        $this->addControlButtons(['cancelLink' => \pm_Context::getModulesListUrl()]);
    }

    public function process()
    {
        $config = new ConfigClass;
        $json = $this->getValue('json');

        $config->setJsonInSession($json);

        $config->save($json);
    }
}
