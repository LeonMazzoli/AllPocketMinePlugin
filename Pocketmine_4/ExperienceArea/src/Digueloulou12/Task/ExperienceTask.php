<?php

namespace Digueloulou12\Task;

use Digueloulou12\ExperienceArea;
use pocketmine\scheduler\Task;
use pocketmine\Server;

class ExperienceTask extends Task
{
    public function onRun(): void
    {
        $world = Server::getInstance()->getWorldManager()->getWorldByName(ExperienceArea::getInstance()->getConfig()->get("world"));
        foreach ($world->getPlayers() as $player) {
            if (ExperienceArea::getInstance()->isInArea($player)) {
                $player->getXpManager()->addXp(ExperienceArea::getInstance()->getConfig()->get("add_xp"));
                $player->sendTip(str_replace("{xp}", ExperienceArea::getInstance()->getConfig()->get("add_xp"), ExperienceArea::getInstance()->getConfig()->get("tip")));
            }
        }
    }
}