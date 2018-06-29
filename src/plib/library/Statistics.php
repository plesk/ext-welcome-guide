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
                $actions[$langKey][] = $value['taskId'] . (isset($value['extensionId']) ? ':' . $value['extensionId'] : '');
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

        return false;
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

        if (empty($increaseValue)) {
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

        if (empty($decreaseValue)) {
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
}
