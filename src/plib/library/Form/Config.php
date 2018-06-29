<?php

namespace PleskExt\Welcome\Form;

use PleskExt\Welcome\Config as ConfigClass;
use PleskExt\Welcome\Session;

class Config extends \pm_Form_Simple
{
    const FORM_JSON_KEY = 'form_json';

    public function init()
    {
        parent::init();

        $session = new Session;
        $config = new ConfigClass;
        $value = $session->get(self::FORM_JSON_KEY);

        if ($value === null) {
            $value = $config->load();
        }

        $session->set(self::FORM_JSON_KEY, null);

        $this->addElement('textarea', 'json', [
            'label' => $this->lmsg('index.config.label.json'),
            'value' => $value,
            'required' => true,
            'style' => 'width: 90%;',
            'rows' => 35,
        ]);

        $this->addControlButtons(['cancelLink' => \pm_Context::getModulesListUrl()]);
    }

    public function process()
    {
        $session = new Session;
        $config = new ConfigClass;
        $json = $this->getValue('json');

        $session->set(self::FORM_JSON_KEY, $json);

        $config->save($json);

        $session->set(self::FORM_JSON_KEY, null);
    }
}
