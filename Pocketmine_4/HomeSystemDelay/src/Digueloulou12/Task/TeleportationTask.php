<?php

namespace Digueloulou12\Task;

use pocketmine\entity\effect\VanillaEffects;
use Digueloulou12\HomeSystemDelay;
use pocketmine\scheduler\Task;
use pocketmine\world\Position;
use pocketmine\player\Player;

class TeleportationTask extends Task
{
    private Position $startposition;
    private Player $player;
    private Position $pos;
    private string $msg;
    private int $timer;

    public function __construct(Player $player, Position $pos, string $msg)
    {
        $this->msg = $msg;
        $this->pos = $pos;
        $this->player = $player;
        $this->startposition = $player->getPosition();
        $this->timer = HomeSystemDelay::getConfigValue("home_time");
        HomeSystemDelay::getInstance()->getScheduler()->scheduleDelayedRepeatingTask($this, 20, 20);
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
            $player->sendTip(HomeSystemDelay::getConfigReplace("home_cooldown", ["{time}"], [$this->timer]));
            $this->timer--;
        } else {
            $player->sendMessage(HomeSystemDelay::getConfigReplace("home_no_teleportation"));
            $player->getEffects()->remove(VanillaEffects::BLINDNESS());
            $this->getHandler()->cancel();
            return;
        }

        if ($this->timer === 0) {
            $player->getEffects()->remove(VanillaEffects::BLINDNESS());
            $player->teleport($this->pos);
            $player->sendTip($this->msg);
            $this->getHandler()->cancel();
            return;
        }
    }
}