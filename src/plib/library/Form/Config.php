<?php

namespace PleskExt\Welcome\Form;

class Config extends \pm_Form_Simple
{
    public function init()
    {
        parent::init();

        $config = new \PleskExt\Welcome\Config;

        $this->addElement('textarea', 'json', [
            'label' => $this->lmsg('index.config.label.json'),
            'value' => $config->load(),
            'required' => true,
            'style' => 'width: 40%;',
        ]);

        $this->addControlButtons(['cancelLink' => \pm_Context::getModulesListUrl()]);
    }

    public function process()
    {
        $config = new \PleskExt\Welcome\Config;
        $json = $this->getValue('json');

        $config->save($json);
    }
}
