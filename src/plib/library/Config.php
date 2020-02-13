<?php
// Copyright 1999-2020. Plesk International GmbH. All rights reserved.

namespace PleskExt\Welcome;

use PleskExt\Welcome\Session;

class Config
{
    const CONFIG_FILE = 'config.json';
    const PRESET_DIR = 'presets';
    const DEFAULT_LOCALE = 'en-US';
    const DEFAULT_LOCALE_KEY = 'default';
    const EXTENSION_ENABLED_KEY = 'isExtensionEnabled';
    const PRESET_FILE_PATH_KEY = 'configPresetFilePath';
    const FORM_JSON_KEY = 'formJson';

    /**
     * @var string
     */
    private $currentLocale;

    /**
     * @var Progress
     */
    private $progress;

    /**
     * @var \pm_Client
     */
    private $client;

    /**
     * @var Session
     */
    private $session;

    public function __construct()
    {
        $this->currentLocale = \pm_Locale::getCode();
        $this->progress = new Progress;
        $this->client = \pm_Session::getClient();
        $this->session = new Session;
    }

    /**
     * @param array  $arr
     * @param string $error
     *
     * @return bool
     */
    private function validateLanguage(array $arr, &$error = '')
    {
        if (!isset($arr['title'])) {
            $error = 'Missing required parameter: title';

            return false;
        }

        if (!isset($arr['description'])) {
            $error = 'Missing required parameter: description';

            return false;
        }

        if (!isset($arr['groups'])) {
            $error = 'Missing required parameter: groups';

            return false;
        }

        if (!is_array($arr['groups']) || empty($arr['groups'])) {
            $error = 'At least one group must be defined';

            return false;
        }

        $actions = isset($arr['actions']) ? $arr['actions'] : [];

        foreach ($arr['groups'] as $groupIdx => $group) {
            if (!isset($group['title'])) {
                $error = "Group #{$groupIdx} is missing required attribute: title";

                return false;
            }

            if (!isset($group['steps'])) {
                $error = "Group '{$group['title']}' is missing required attribute: steps";

                return false;
            }

            if (!is_array($group['steps']) || empty($group['steps'])) {
                $error = "Group '{$group['title']}' must define at least one step";

                return false;
            }

            foreach ($group['steps'] as $stepIdx => $step) {
                if (!isset($step['title'])) {
                    $error = "Step #{$stepIdx} in group '{$group['title']}' is missing required attribute: title";

                    return false;
                }

                if (isset($step['buttons'])) {
                    foreach ($step['buttons'] as $buttonIdx => $button) {
                        if (!isset($button['actionId'])) {
                            $error = "Button #{$buttonIdx} in step #{$stepIdx} in group '{$group['title']}' is missing required attribute: actionId";

                            return false;
                        } else {
                            if (!isset($actions[$button['actionId']])) {
                                $error = "Button #{$buttonIdx} in step #{$stepIdx} in group '{$group['title']}' uses undefined action: {$button['actionId']}";

                                return false;
                            }
                        }
                    }
                }
            }
        }

        return true;
    }

    /**
     * @param string $json
     * @param string $error
     *
     * @return bool
     */
    private function validate($json, &$error = '')
    {
        $arr = json_decode($json, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            $error = 'Invalid or malformed JSON';

            return false;
        }

        if (!isset($arr[self::DEFAULT_LOCALE_KEY])) {
            $error = 'Missing "default" language';

            return false;
        }

        foreach ($arr as $langCode => $langData) {
            $isValidLangData = $this->validateLanguage($langData, $langError);

            if (!$isValidLangData) {
                $error = $langError . " (language: {$langCode})";

                return false;
            }
        }

        return true;
    }

    /**
     * @param string $text
     *
     * @return string|string[]
     * @throws \Exception
     */
    private function replace($text)
    {
        $text = htmlspecialchars(strip_tags($text));
        $text = str_replace(["\r\n", "\r", "\n"], '<br />', $text);

        preg_match_all('/%%+(.*?)%%/', $text, $matches, PREG_SET_ORDER);

        foreach ($matches as $match) {
            $placeholder = $match[0];
            $segments = explode('|', $match[1]);
            $type = $segments[0];

            if ($type === 'username') {
                $currentUserName = \pm_Session::getClient()->getProperty('pname');

                if (empty($currentUserName)) {
                    $currentUserName = \pm_Session::getClient()->getProperty('login');
                }

                $text = str_replace($placeholder, $currentUserName, $text);
            } elseif ($type === 'extname') {
                if (count($segments) !== 2) {
                    throw new \Exception('Invalid number of parameters for type: ' . $type);
                }

                $extensionId = $segments[1];
                $extensionName = (new Extension($extensionId))->getName();

                if ($extensionName === false) {
                    $extensionName = '[Extension "' . $extensionId . '" does not exist]';
                }

                $text = str_replace($placeholder, $extensionName, $text);
            } elseif ($type === 'image') {
                if (count($segments) < 2) {
                    throw new \Exception('Invalid number of parameters for type: ' . $type);
                }

                $url = $segments[1];
                $style = '';

                if (isset($segments[2]) && in_array($segments[2], ['left', 'right'])) {
                    $style = 'style="float: ' . $segments[2] . ';" ';
                }

                $text = str_replace($placeholder, '<img src="' . $url . '" ' . $style . '/>', $text);
            } elseif ($type === 'format') {
                if (count($segments) !== 3) {
                    throw new \Exception('Invalid number of parameters for type: ' . $type);
                }

                $format = $segments[1];
                $str = $segments[2];

                $formats = [
                    'bold'      => [
                        'before' => '<strong>',
                        'after'  => '</strong>',
                    ],
                    'italic'    => [
                        'before' => '<em>',
                        'after'  => '</em>',
                    ],
                    'underline' => [
                        'before' => '<u>',
                        'after'  => '</u>',
                    ],
                ];

                if (!isset($formats[$format])) {
                    throw new \Exception('Unknown format: ' . $format);
                }

                $text = str_replace($placeholder, $formats[$format]['before'] . $str . $formats[$format]['after'], $text);
            } else {
                throw new \Exception('Invalid type: ' . $type);
            }
        }

        return $text;
    }

    /**
     * @return string
     */
    public function load()
    {
        $configPresetFilePath = \pm_Settings::get(self::PRESET_FILE_PATH_KEY);

        if (!empty($configPresetFilePath) && file_exists($configPresetFilePath)) {
            return file_get_contents($configPresetFilePath);
        }

        return file_get_contents(\pm_Context::getVarDir() . self::CONFIG_FILE);
    }

    /**
     * @param string $actionId
     * @param array  $actions
     *
     * @return array
     * @throws \Exception
     */
    private function renderButton($actionId, array $actions)
    {
        $action = $actions[$actionId];
        $buttonTarget = '_self';

        if ($action['taskId'] === 'install') {
            $extension = new Extension($action['extensionId']);
            $isInstalled = $extension->isInstalled();

            $buttonTitle = isset($action['titleInstall']) ? $action['titleInstall'] : \pm_Locale::lmsg('library.config.button.title.install');
            $taskId = $action['taskId'];

            if ($isInstalled) {
                $buttonTitle = isset($action['titleOpen']) ? $action['titleOpen'] : \pm_Locale::lmsg('library.config.button.title.open');
                $taskId = 'open';
            }

            $buttonUrl = $isInstalled ? $extension->createOpenLink() : $extension->createInstallLink();

            return [$buttonTitle, $buttonUrl, $buttonTarget, $taskId,];
        } elseif ($action['taskId'] === 'extlink') {
            $extension = new Extension($action['extensionId']);
            $buttonTitle = isset($action['title']) ? $action['title'] : $extension->getName();
            $buttonUrl = $extension->createOpenLink();

            if ($buttonTitle === false) {
                $buttonTitle = '[Extension "' . $action['extensionId'] . '" does not exist]';
            }

            return [$buttonTitle, $buttonUrl, $buttonTarget, $action['taskId']];
        } elseif ($action['taskId'] === 'link') {
            $buttonTitle = $action['title'];
            $buttonUrl = $action['url'];
            $buttonTarget = isset($action['newWindow']) ? '_blank' : '_self';

            return [$buttonTitle, $buttonUrl, $buttonTarget];
        } elseif ($action['taskId'] === 'addDomain') {
            $buttonTitle = isset($action['title']) ? $action['title'] : \pm_Locale::lmsg('library.config.button.title.adddomain');
            $buttonUrl = Helper::getLinkNewDomain();

            return [$buttonTitle, $buttonUrl, $buttonTarget, $action['taskId']];
        } else {
            throw new \Exception('Invalid task ID: ' . $action['taskId']);
        }
    }

    /**
     * @param bool $jsonEncode
     *
     * @return false|string
     * @throws \Exception
     */
    public function getProcessedConfigData($jsonEncode = false)
    {
        $json = $this->load();
        $arr = json_decode($json, true);
        $locale = isset($arr[$this->currentLocale]) ? $this->currentLocale : self::DEFAULT_LOCALE_KEY;

        $arr[$locale]['title'] = $this->replace($arr[$locale]['title']);
        $arr[$locale]['description'] = $this->replace($arr[$locale]['description']);

        foreach ($arr[$locale]['groups'] as $groupIdx => $group) {
            $arr[$locale]['groups'][$groupIdx]['title'] = $this->replace($group['title']);

            foreach ($group['steps'] as $stepIdx => $step) {
                $arr[$locale]['groups'][$groupIdx]['steps'][$stepIdx]['title'] = $this->replace($step['title']);
                $arr[$locale]['groups'][$groupIdx]['steps'][$stepIdx]['description'] = $this->replace($step['description']);

                if (isset($step['buttons'])) {
                    foreach ($step['buttons'] as $buttonIdx => $button) {
                        list($buttonTitle, $buttonUrl, $buttonTarget, $taskId) = $this->renderButton($button['actionId'], $arr[$locale]['actions']);

                        $arr[$locale]['groups'][$groupIdx]['steps'][$stepIdx]['buttons'][$buttonIdx]['title'] = $buttonTitle;
                        $arr[$locale]['groups'][$groupIdx]['steps'][$stepIdx]['buttons'][$buttonIdx]['url'] = $buttonUrl;
                        $arr[$locale]['groups'][$groupIdx]['steps'][$stepIdx]['buttons'][$buttonIdx]['target'] = $buttonTarget;
                        $arr[$locale]['groups'][$groupIdx]['steps'][$stepIdx]['buttons'][$buttonIdx]['taskId'] = $taskId;
                    }
                }

                $arr[$locale]['groups'][$groupIdx]['steps'][$stepIdx]['completed'] = $this->progress->isStepCompleted($groupIdx, $stepIdx);
                $arr[$locale]['groups'][$groupIdx]['steps'][$stepIdx]['indexGroup'] = $groupIdx;
                $arr[$locale]['groups'][$groupIdx]['steps'][$stepIdx]['index'] = $stepIdx;
            }
        }

        if ($jsonEncode) {
            return json_encode($arr[$locale]);
        }

        return $arr[$locale];
    }

    /**
     * @param string $json
     * @param bool   $isUploadedFile
     *
     * @throws \Zend_Db_Table_Exception
     * @throws \Zend_Db_Table_Row_Exception
     * @throws \pm_Exception_InvalidArgumentException
     */
    public function save($json, $isUploadedFile = false)
    {
        if (!$this->validate($json, $error)) {
            $prefix = $isUploadedFile ? 'File upload error' : 'JSON validation failed';

            throw new \InvalidArgumentException($prefix . ': ' . $error);
        }

        file_put_contents(\pm_Context::getVarDir() . self::CONFIG_FILE, $json);
        $this->progress->clearProgress();
        $this->session->set(self::FORM_JSON_KEY, null);

        \pm_Settings::set(self::PRESET_FILE_PATH_KEY, \pm_Context::getVarDir() . self::CONFIG_FILE);
        (new Statistics())->setPresetValue();
        (new Statistics())->setActionList();
        (new Statistics())->setButtonClickList();
    }

    /**
     * @param string $name
     *
     * @throws \Zend_Db_Table_Exception
     * @throws \Zend_Db_Table_Row_Exception
     * @throws \pm_Exception_InvalidArgumentException
     */
    public function updateDefaultConfigFromPreset($name)
    {
        $presetFile = \pm_Context::getVarDir() . self::PRESET_DIR . '/' . $name . '.json';

        if (!file_exists($presetFile)) {
            throw new \InvalidArgumentException('Unknown configuration preset: ' . $name);
        }

        if (file_exists(\pm_Context::getVarDir() . self::CONFIG_FILE)) {
            unlink(\pm_Context::getVarDir() . self::CONFIG_FILE);
        }

        copy($presetFile, \pm_Context::getVarDir() . self::CONFIG_FILE);
        $this->progress->clearProgress();

        \pm_Settings::set(self::PRESET_FILE_PATH_KEY, $presetFile);
        (new Statistics())->setPresetValue();
        (new Statistics())->setActionList();
        (new Statistics())->setButtonClickList();
    }

    /**
     * @param string $name
     *
     * @throws \Zend_Db_Table_Exception
     * @throws \Zend_Db_Table_Row_Exception
     * @throws \pm_Exception_InvalidArgumentException
     */
    public function createDefaultConfigFromPreset($name)
    {
        if (file_exists(\pm_Context::getVarDir() . self::CONFIG_FILE)) {
            return;
        }

        $this->updateDefaultConfigFromPreset($name);
    }

    /**
     * @return array
     */
    public function getPresets()
    {
        $paths = glob(\pm_Context::getVarDir() . self::PRESET_DIR . '/*.json');
        $presets = [];

        foreach ($paths as $path) {
            if (!file_exists($path)) {
                continue;
            }

            $presets[] = pathinfo($path, PATHINFO_FILENAME);
        }

        return $presets;
    }

    /**
     * Sets a pre-defined name for the shipped presets
     *
     * @param $preset
     *
     * @return mixed
     */
    public function getPreparedPresetName($preset)
    {
        $presetNames = [
            'wordpress' => 'WordPress',
            'business'  => 'Business & Collaboration',
            'default'   => 'Default',
        ];

        if (!empty($presetNames[$preset])) {
            return $presetNames[$preset];
        }

        return $preset;
    }

    /**
     * @param string $preset
     *
     * @return string
     */
    public function getPresetConfig($preset)
    {
        $file = \pm_Context::getVarDir() . self::PRESET_DIR . '/' . $preset . '.json';

        if (file_exists($file)) {
            return file_get_contents($file);
        }

        return '';
    }

    /**
     * @return bool
     */
    public function isExtensionEnabled()
    {
        $num = (int)$this->client->getSetting(self::EXTENSION_ENABLED_KEY, 1);

        return ($num > 0) ? true : false;
    }

    public function disableExtension()
    {
        $this->client->setSetting(self::EXTENSION_ENABLED_KEY, 0);
        (new Statistics())->increaseHiddenByUserValue();
    }

    public function enableExtension()
    {
        $this->client->setSetting(self::EXTENSION_ENABLED_KEY, 1);
        (new Statistics())->decreaseHiddenByUserValue();
    }

    public function getConfigFilePath()
    {
        return \pm_Settings::get(self::PRESET_FILE_PATH_KEY);
    }

    /**
     * @return string
     */
    public function getJsonFromSession()
    {
        $json = $this->session->get(self::FORM_JSON_KEY);

        if ($json === null) {
            $json = $this->load();
        }

        $this->session->set(self::FORM_JSON_KEY, null);

        return $json;
    }

    /**
     * @param string $json
     */
    public function setJsonInSession($json)
    {
        $this->session->set(self::FORM_JSON_KEY, $json);
    }
}
