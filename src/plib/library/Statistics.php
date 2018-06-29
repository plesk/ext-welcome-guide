<?php
// Copyright 1999-2018. Plesk International GmbH. All rights reserved.

namespace PleskExt\Welcome;

class Statistics
{
    const SETTINGS_NAME = 'statistics';

    /**
     * Gets all statistics values
     *
     * @param bool $json
     *
     * @return null|string
     */
    public function getStatistics($json = false)
    {
        $statistics = $this->getSettings();

        if (!$json) {
            return $statistics;
        }

        return json_encode($statistics);
    }

    /**
     * Sets the current preset value
     *
     * @return string
     */
    public function setPresetValue()
    {
        $configFilePath = (new Config())->getConfigFilePath();
        $configFile = 'unknown';

        if (!empty($configFilePath)) {
            $configFile = basename($configFilePath, '.json');
        }

        $this->set('presetName', $configFile);

        return $configFile;
    }

    /**
     * Sets the actions value of the currently used preset
     *
     * @return array
     */
    public function setActionList()
    {
        $actions = [];
        $configFileContent = json_decode((new Config())->load(), true);

        foreach ($configFileContent as $langKey => $langValue) {
            foreach ($langValue['actions'] as $value) {
                $actions[$langKey][] = $this->createButtonId($value);
            }
        }

        $this->set('actionList', $actions);

        return $actions;
    }

    /**
     * Increases hidden by user value - triggered if Welcome box is disabled
     */
    public function increaseHiddenByUserValue()
    {
        $this->increase('hiddenByUser', 1);
    }

    /**
     * Decreases hidden by user value
     */
    public function decreaseHiddenByUserValue()
    {
        $this->decrease('hiddenByUser', 1);
    }

    /**
     * Increases was already used value - triggered if Welcome box is disabled
     */
    public function increaseExtensionsInstalledValue()
    {
        $this->increase('extensionsInstalled', 1);
    }

    /**
     * @param int $groupId
     * @param int $stepId
     */
    public function increaseButtonClickCount($groupId, $stepId)
    {
        $buttonClicks = $this->get('buttonClicks');
        $configFileContent = json_decode((new Config())->load(), true);

        if ($buttonClicks === null) {
            $buttonClicks = [];

            foreach ($configFileContent as $langKey => $langValue) {
                $buttonClicks[$langKey] = [];

                foreach ($langValue['actions'] as $value) {
                    $buttonId = $this->createButtonId($value);
                    $buttonClicks[$langKey][$buttonId] = 0;
                }
            }
        }

        $locale = (\pm_Locale::getCode() === Config::DEFAULT_LOCALE) ? Config::DEFAULT_LOCALE_KEY : \pm_Locale::getCode();

        if (isset($configFileContent[$locale])) {
            $lang = $configFileContent[$locale];

            if (isset($lang['groups'][$groupId])) {
                $group = $lang['groups'][$groupId];

                if (isset($group['steps'][$stepId])) {
                    $step = $group['steps'][$stepId];
                    $actionId = $step['buttons'][0]['actionId'];
                    $action = $lang['actions'][$actionId];
                    $buttonId = $this->createButtonId($action);

                    if (isset($buttonClicks[$locale]) && isset($buttonClicks[$locale][$buttonId])) {
                        $buttonClicks[$locale][$buttonId]++;
                    }
                }
            }
        }

        $this->set('buttonClicks', $buttonClicks);
    }

    /**
     * Gets the statistics value for a specific name
     *
     * @param $name
     *
     * @return null
     */
    private function get($name)
    {
        $statistics = $this->getSettings();

        if (isset($statistics[$name])) {
            return $statistics[$name];
        }

        return null;
    }

    /**
     * Sets a specific statistics value
     *
     * @param $name
     * @param $value
     */
    private function set($name, $value)
    {
        $statistics = $this->getSettings();
        $statistics[$name] = $value;
        $this->setSettings($statistics);
    }

    /**
     * Increases a specific statistics value
     *
     * @param $name
     * @param $amount
     *
     * @return mixed
     */
    private function increase($name, $amount)
    {
        $increaseValue = $this->get($name);

        if ($increaseValue === null) {
            $increaseValue = 0;
        }

        $increaseValue += $amount;
        $this->set($name, $increaseValue);

        return $increaseValue;
    }

    /**
     * Decreases a specific statistics value
     *
     * @param $name
     * @param $amount
     *
     * @return mixed
     */
    private function decrease($name, $amount)
    {
        $decreaseValue = $this->get($name);

        if ($decreaseValue === null) {
            $decreaseValue = 0;
        }

        $decreaseValue -= $amount;

        if ($decreaseValue < 0) {
            $decreaseValue = 0;
        }

        $this->set($name, $decreaseValue);

        return $decreaseValue;
    }

    /**
     * Gets the settings value for statistics
     *
     * @return null|string
     */
    private function getSettings()
    {
        return json_decode(\pm_Settings::get(self::SETTINGS_NAME, ''), true);
    }

    /**
     * Sets the settings value for statistics
     *
     * @param $statistics
     */
    private function setSettings($statistics)
    {
        \pm_Settings::set(self::SETTINGS_NAME, json_encode($statistics));
    }

    /**
     * Sets the settings value for statistics
     */
    private function clearSettings()
    {
        \pm_Settings::set(self::SETTINGS_NAME, null);
    }

    private function createButtonId(array $action)
    {
        $buttonId = $action['taskId'];

        if (isset($action['extensionId'])) {
            $buttonId .= ':' . $action['extensionId'];
        }

        return $buttonId;
    }
}
