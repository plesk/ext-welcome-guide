<?php
// Copyright 1999-2020. Plesk International GmbH. All rights reserved.

use PleskExt\Welcome\Statistics;

class Modules_Welcome_CustomInfo implements pm_Hook_Interface
{
    /**
     * @return array
     */
    private function getStats()
    {
        $stats = [
            'presetName'     => '',
            'actionList'     => [],
            'hiddenByUser'   => 0,
            'buttonClicks'   => [],
            'checkedSteps'   => 0,
            'completedSteps' => 0,
        ];

        $statistics = (new Statistics())->getStatistics();
        $stats = array_merge($stats, $statistics);

        return $stats;
    }

    /**
     * @return string
     */
    public function getInfo()
    {
        return json_encode($this->getStats());
    }
}
