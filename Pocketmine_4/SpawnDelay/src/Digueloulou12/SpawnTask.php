<?php

namespace Digueloulou12;

use pocketmine\entity\effect\VanillaEffects;
use pocketmine\scheduler\Task;
use pocketmine\world\Position;
use pocketmine\player\Player;

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

    public function onRun(): void
    {
        $player = $this->player;
        if (!$player->isOnline()) {
            $this->getHandler()->cancel();
            return;
        }

        if ($player->getPosition()->getFloorX() === $this->startposition->getFloorX() and
            $player->getPosition()->getFloorY() === $this->startposition->getFloorY() and
            $player->getPosition()->getFloorZ() === $this->startposition->getFloorZ()) {
            $player->sendTip(SpawnDelay::getConfigReplace("cooldown", ["{time}"], [$this->timer]));
            $this->timer--;
        } else {
            $player->sendMessage(SpawnDelay::getConfigReplace("cancel"));
            $player->getEffects()->remove(VanillaEffects::BLINDNESS());
            $this->getHandler()->cancel();
            return;
        }

        if ($this->timer === 0) {
            $player->getEffects()->remove(VanillaEffects::BLINDNESS());
            $player->teleport($player->getWorld()->getSafeSpawn());
            $player->sendTip(SpawnDelay::getConfigReplace("teleportation"));
            $this->getHandler()->cancel();
            return;
        }
    }
}