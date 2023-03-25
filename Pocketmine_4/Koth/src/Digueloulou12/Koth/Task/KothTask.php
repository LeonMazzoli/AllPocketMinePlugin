<?php

namespace Digueloulou12\Koth\Task;

use Digueloulou12\Koth\API\KothAPI;
use Digueloulou12\Koth\Utils\Utils;
use pocketmine\player\Player;
use pocketmine\scheduler\Task;

class KothTask extends Task
{
    public static ?Player $player = null;
    public static int $time = 60;

    public function onRun(): void
    {
        if (self::$player !== null) {
            $player = self::$player;
            if ($player->isOnline()) {
                if (KothAPI::isInArea($player)) {
                    if (self::$time !== 0) {
                        $player->sendPopup(Utils::getConfigReplace("koth_king"));
                        self::$time--;
                    } else KothAPI::finishKoth();
                } else KothAPI::leaveKoth();
            } else KothAPI::leaveKoth();
        } else KothAPI::searchPlayer();
    }
}