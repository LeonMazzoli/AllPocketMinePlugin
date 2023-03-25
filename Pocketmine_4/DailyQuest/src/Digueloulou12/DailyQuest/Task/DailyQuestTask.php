<?php

namespace Digueloulou12\DailyQuest\Task;

use Digueloulou12\DailyQuest\API\DailyQuestAPI;
use Digueloulou12\DailyQuest\Utils\Utils;
use pocketmine\scheduler\Task;

class DailyQuestTask extends Task
{
    public function onRun(): void
    {
        date_default_timezone_set(Utils::getConfigValue("time_zone"));
        if (date("H") === "00") DailyQuestAPI::updateQuest();
    }
}