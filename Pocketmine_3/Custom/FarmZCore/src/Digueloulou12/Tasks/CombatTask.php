<?php

namespace Digueloulou12\Tasks;

use Digueloulou12\Main;
use pocketmine\Player;
use pocketmine\scheduler\Task;
use pocketmine\Server;

class CombatTask extends Task
{
    public static array $combat = [];
    public static int $seconds;

    public function __construct()
    {
        self::$seconds = Main::getConfigAPI()->getConfigValue("combat_time");
    }

    public function onRun(int $currentTick)
    {
        foreach (self::$combat as $player => $time) {
            if ((time() - $time) > self::$seconds) {
                $sender = Server::getInstance()->getPlayer($player);
                if ($sender instanceof Player) $sender->sendMessage(Main::getConfigAPI()->getConfigValue("combat_stop"));
                unset(self::$combat[$player]);
            }
        }
    }
}