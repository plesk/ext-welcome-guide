<?php
// Copyright 1999-2020. Plesk International GmbH. All rights reserved.

namespace PleskExt\Welcome;

class Statistics
{
    const STATISTICS_FILE = 'default/statistics.json';

    /**
     * Gets all statistics values
     *
     * @param bool $json
     *
     * @return null|string
     */
    public function getStatistics($json = false)
    {
        $statistics = $this->fixNumericIndexes($this->getSettings());

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
                $actions[$langKey][$this->createButtonId($value)] = 1;
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
     * Increases checked steps value - triggered if step is checked by user
     */
    public function increaseCompletedStepsValue()
    {
        $this->increase('completedSteps', 1);
    }

    /**
     * Decreases checked steps value
     */
    public function decreaseCompletedStepsValue()
    {
        $this->decrease('completedSteps', 1);
    }

    /**
     * @return array
     */
    public function setButtonClickList()
    {
        $configFileContent = json_decode((new Config())->load(), true);
        $buttonClicks = [];

        foreach ($configFileContent as $langKey => $langValue) {
            $buttonClicks[$langKey] = [];

            foreach ($langValue['actions'] as $value) {
                $buttonId = $this->createButtonId($value);
                $buttonClicks[$langKey][$buttonId] = 0;
            }
        }

        $this->set('buttonClicks', $buttonClicks);

        return $buttonClicks;
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
            $buttonClicks = $this->setButtonClickList();
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
        $statisticsData = [];

        if (file_exists(\pm_Context::getVarDir() . self::STATISTICS_FILE)) {
            $statisticsDataJson = file_get_contents(\pm_Context::getVarDir() . self::STATISTICS_FILE);

            if (!empty($statisticsDataJson)) {
                $statisticsData = json_decode($statisticsDataJson, true);
            }
        }

        return $statisticsData;
    }

    /**
     * Sets the settings value for statistics
     *
     * @param $statistics
     */
    private function setSettings($statistics)
    {
        file_put_contents(\pm_Context::getVarDir() . self::STATISTICS_FILE, json_encode($statistics));
    }

    /**
     * Sets the settings value for statistics
     */
    private function clearSettings()
    {
        unlink(\pm_Context::getVarDir() . self::STATISTICS_FILE);
    }

    private function createButtonId(array $action)
    {
        $buttonId = $action['taskId'];

        if (isset($action['extensionId'])) {
            $buttonId .= ':' . $action['extensionId'];
        }

        return $buttonId;
    }

    private function hasStringKeys(array $array)
    {
        return count(array_filter(array_keys($array), 'is_string')) > 0;
    }

    /**
     * @param mixed: $data
     *
     * @return mixed
     */
    private function fixNumericIndexes($data)
    {
        if (!is_array($data)) {
            return $data;
        }
        $dataToReturn = [];
        if ($this->hasStringKeys($data)) {
            foreach ($data as $key => $value) {
                $key = is_numeric($key) ? 's' . (string)$key : $key;
                $dataToReturn[$key] = $this->fixNumericIndexes($value);
            }
        } else {
            $dataToReturn = ['TYPE' => 'array', 'VALUE' => [array_map([$this, 'fixNumericIndexes'], $data)]];
        }

        return $dataToReturn;
    }
}
