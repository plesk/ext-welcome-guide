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

        $arr[$groupId] = $stepId;

        $this->client->setSetting(self::PROGRESS_SETTING_KEY, json_encode($arr));
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

        return ($stepId > $arr[$groupId]) ? false : true;
    }
}
