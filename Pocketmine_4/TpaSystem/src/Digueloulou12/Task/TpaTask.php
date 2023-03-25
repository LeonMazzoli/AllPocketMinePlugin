<?php

namespace Digueloulou12\Task;

use pocketmine\entity\effect\VanillaEffects;
use pocketmine\scheduler\Task;
use pocketmine\world\Position;
use pocketmine\player\Player;
use Digueloulou12\TpaSystem;

class TpaTask extends Task
{
    private Position $startposition;
    private Player $player;
    private Position $pos;
    private int $timer;

    public function __construct(Player $player, Position $pos)
    {
        $this->pos = $pos;
        $this->player = $player;
        $this->startposition = $player->getPosition();
        $this->timer = TpaSystem::getConfigValue("tpa_delay");
        TpaSystem::getInstance()->getScheduler()->scheduleDelayedRepeatingTask($this, 20, 20);
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
            $player->sendTip(TpaSystem::getConfigReplace("tpa_cooldown", ["{time}"], [$this->timer]));
            $this->timer--;
        } else {
            $player->sendMessage(TpaSystem::getConfigReplace("tpa_no_teleportation"));
            $player->getEffects()->remove(VanillaEffects::BLINDNESS());
            $this->getHandler()->cancel();
            return;
        }

        if ($this->timer === 0) {
            $player->getEffects()->remove(VanillaEffects::BLINDNESS());
            TpaSystem::$players[$player->getName()] = time() + TpaSystem::getConfigValue("tpa_invincibility");
            $player->teleport($this->pos);
            $this->getHandler()->cancel();
            return;
        }
    }
}