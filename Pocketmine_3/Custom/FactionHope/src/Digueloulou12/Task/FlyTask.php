<?php

namespace Digueloulou12\Task;

use pocketmine\Server;
use pocketmine\scheduler\Task;
use Digueloulou12\API\FactionAPI;

class FlyTask extends Task
{
    public function onRun(int $currentTick)
    {
        foreach (Server::getInstance()->getOnlinePlayers() as $sender) {
            if ($sender->getGamemode() !== 1) {
                if ($sender->isFlying()) {
                    if (FactionAPI::isChunkClaim($sender->getLevel()->getChunkAtPosition($sender))) {
                        if (FactionAPI::getFactionClaim($sender->getLevel()->getChunkAtPosition($sender)) !== FactionAPI::getFactionPlayer($sender)) {
                            $sender->setFlying(false);
                            $sender->setAllowFlight(false);
                        }
                    } else {
                        $sender->setFlying(false);
                        $sender->setAllowFlight(false);
                    }
                }
            }
        }
    }
}