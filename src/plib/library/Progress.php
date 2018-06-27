<?php

namespace PleskExt\Welcome;

class Progress
{
    const PROGRESS_SETTING_KEY = 'welcome_progress';

    /**
     * @var \pm_Client
     */
    private $client;

    public function __construct()
    {
        $this->client = \pm_Session::getClient();
    }

    /**
     * @return array
     */
    public function getProgress()
    {
        $json = $this->client->getSetting(self::PROGRESS_SETTING_KEY, '{}');

        return json_decode($json, true);
    }

    /**
     * @param int $groupId
     * @param int $stepId
     */
    public function completeStep($groupId, $stepId)
    {
        $arr = $this->getProgress();

        if (!isset($arr[$groupId]))
        {
            $arr[$groupId] = [];
        }

        if (!isset($arr[$groupId][$stepId]))
        {
            $arr[$groupId][$stepId] = true;
        }
        else
        {
            $arr[$groupId][$stepId] = !$arr[$groupId][$stepId];
        }

        $this->client->setSetting(self::PROGRESS_SETTING_KEY, json_encode($arr, JSON_FORCE_OBJECT));
    }

    /**
     * @param int $groupId
     * @param int $stepId
     *
     * @return bool
     */
    public function isStepCompleted($groupId, $stepId)
    {
        $arr = $this->getProgress();

        if (!isset($arr[$groupId]))
        {
            return false;
        }

        if (!isset($arr[$groupId][$stepId]))
        {
            return false;
        }

        return $arr[$groupId][$stepId];
    }

    public function clearProgress()
    {
        foreach ($this->client->getAll() as $client)
        {
            $client->setSetting(self::PROGRESS_SETTING_KEY, null);
        }
    }
}
