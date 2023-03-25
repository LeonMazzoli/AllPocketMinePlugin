<?php

namespace Digueloulou12\Tasks;

use Digueloulou12\API\ConfigAPI;
use Digueloulou12\Main;
use pocketmine\entity\object\ExperienceOrb;
use pocketmine\entity\object\ItemEntity;
use pocketmine\scheduler\Task;
use pocketmine\Server;

class ClearLaggTask extends Task
{
    public static $time = 300;

    public function __construct(int $time = 300)
    {
        self::$time = $time;
    }

    public function onRun(int $tick)
    {
        $time = self::$time;
        if (in_array($time, ConfigAPI::getConfigArray("time_chat"))) {
            foreach (Server::getInstance()->getOnlinePlayers() as $player) {
                $player->sendMessage(Main::getConfigAPI()->getConfigValue("clearlagg_time", ["{time}"], [$time]));
            }
        }

        if ($time == 0) {
            $count = 0;

            foreach (Server::getInstance()->getLevels() as $level) {
                foreach ($level->getEntities() as $entity) {
                    if ($entity instanceof ItemEntity or $entity instanceof ExperienceOrb) {
                        $entity->flagForDespawn();
                        $count++;
                    }
                }
            }

            foreach (Server::getInstance()->getOnlinePlayers() as $player) {
                $player->sendMessage(Main::getConfigAPI()->getConfigValue("clearlagg_clear", ["{count}"], [$count]));
            }
            self::$time = ConfigAPI::getConfigInt("time");
        }
        self::$time = self::$time - 5;
    }
}