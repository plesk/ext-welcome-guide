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
     * @param int $groupIdx
     * @param int $stepIdx
     *
     * @return bool
     */
    public function isStepCompleted($groupIdx, $stepIdx)
    {
        $arr = $this->getProgress();

        if (!isset($arr[$groupIdx]))
        {
            return false;
        }

        return ($stepIdx > $arr[$groupIdx]) ? false : true;
    }
}
