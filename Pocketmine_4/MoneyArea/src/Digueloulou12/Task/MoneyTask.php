<?php

namespace Digueloulou12\Task;

use pocketmine\scheduler\Task;
use Digueloulou12\MoneyArea;
use pocketmine\Server;

class MoneyTask extends Task
{
    public function onRun(): void
    {
        $world = Server::getInstance()->getWorldManager()->getWorldByName(MoneyArea::getInstance()->getConfig()->get("world"));
        foreach ($world->getPlayers() as $player) {
            if (MoneyArea::getInstance()->isInArea($player)) {
                MoneyArea::getInstance()->addMoney($player, MoneyArea::getInstance()->getConfig()->get("money"));
                $player->sendTip(MoneyArea::getInstance()->getConfig()->get("tip"));
            }
        }
    }
}