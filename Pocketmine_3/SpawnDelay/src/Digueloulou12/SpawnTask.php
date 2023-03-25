<?php

namespace Digueloulou12;

use pocketmine\level\Position;
use pocketmine\scheduler\Task;
use pocketmine\entity\Effect;
use pocketmine\Player;

class SpawnTask extends Task
{
    private Position $startposition;
    private Player $player;
    private int $timer;

    public function __construct(Player $player)
    {
        $this->player = $player;
        $this->startposition = $player->getPosition();
        $this->timer = SpawnDelay::getConfigValue("delay");
        SpawnDelay::getInstance()->getScheduler()->scheduleDelayedRepeatingTask($this, 20, 20);
    }

    public function onRun(int $currentTick)
    {
        $player = $this->player;
        if (!$player->isOnline()) {
            $this->getHandler()->cancel();
            return;
        }

        if ($player->getFloorX() === $this->startposition->getFloorX() and
            $player->getFloorY() === $this->startposition->getFloorY() and
            $player->getFloorZ() === $this->startposition->getFloorZ()) {
            $player->sendTip(SpawnDelay::getConfigReplace("cooldown", ["{time}"], [$this->timer]));
            $this->timer--;
        } else {
            $player->sendMessage(SpawnDelay::getConfigReplace("cancel"));
            $player->removeEffect(Effect::BLINDNESS);
            $this->getHandler()->cancel();
            return;
        }

        if ($this->timer === 0) {
            $player->removeEffect(Effect::BLINDNESS);
            $player->teleport($player->getLevel()->getSafeSpawn());
            $player->sendTip(SpawnDelay::getConfigReplace("teleportation"));
            $this->getHandler()->cancel();
            return;
        }
    }
}