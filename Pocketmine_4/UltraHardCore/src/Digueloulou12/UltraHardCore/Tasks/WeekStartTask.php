<?php

namespace Digueloulou12\UltraHardCore\Tasks;

use Digueloulou12\UltraHardCore\UltraHardCore;
use Digueloulou12\UltraHardCore\Utils\Utils;
use pocketmine\scheduler\Task;

class WeekStartTask extends Task
{
    public function onRun(): void
    {
        date_default_timezone_set(Utils::getConfigValue("time_zone"));
        if (date("D H:i") === Utils::getConfigValue("day") . " " . Utils::getConfigValue("hour")) {
            $api = UltraHardCore::getInstance()->getAPI();
            if (!$api->isGame()) {
                $api->startGame();
            }
        }
    }
}