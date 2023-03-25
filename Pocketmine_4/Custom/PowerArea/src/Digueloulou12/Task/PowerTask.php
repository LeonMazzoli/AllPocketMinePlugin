<?php

namespace Digueloulou12\Task;

use pocketmine\scheduler\Task;
use Digueloulou12\PowerArea;
use pocketmine\Server;

class PowerTask extends Task
{
    public function onRun(): void
    {
        $world = Server::getInstance()->getWorldManager()->getWorldByName(PowerArea::getInstance()->getConfig()->get("world"));
        foreach ($world->getPlayers() as $player) {
            if (PowerArea::getInstance()->isInArea($player)) {
                PowerArea::getInstance()->addPower($player, PowerArea::getInstance()->getConfig()->get("power"));
                $player->sendTip(PowerArea::getInstance()->getConfig()->get("tip"));
            }
        }
    }
}