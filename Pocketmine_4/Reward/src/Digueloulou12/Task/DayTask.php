<?php

namespace Digueloulou12\Task;

use pocketmine\scheduler\Task;
use Digueloulou12\Reward;

class DayTask extends Task
{
    public function onRun(): void
    {
        date_default_timezone_set(Reward::getInstance()->getConfigValue("time"));
        if (date("H:i") == "00:00") {
            $config = Reward::$data;
            foreach ($config->getAll() as $player => $key) {
                $config->set($player, ["day" => $key["day"], "max_day" => $key["max_day"] + 1, "loot" => false]);
            }
            $config->save();
        }
    }
}