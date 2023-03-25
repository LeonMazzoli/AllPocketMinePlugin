<?php

namespace Digueloulou12\Task;

use Digueloulou12\API\WarpAPI;
use pocketmine\entity\effect\VanillaEffects;
use pocketmine\scheduler\Task;
use pocketmine\world\Position;
use pocketmine\player\Player;
use Digueloulou12\WarpDelay;

class TeleportationTask extends Task
{
    private Position $start_position;
    private Player $player;
    private string $warp;
    private int $timer;

    public function __construct(Player $player, string $warp)
    {
        $this->warp = $warp;
        $this->player = $player;
        $this->start_position = $player->getPosition();
        $this->timer = WarpDelay::getConfigValue("delay");
        WarpDelay::getInstance()->getScheduler()->scheduleDelayedRepeatingTask($this, 20, 20);
    }

    public function onRun(): void
    {
        $player = $this->player;
        if (!$player->isOnline()) {
            $this->getHandler()->cancel();
            return;
        }

        if ($player->getPosition()->getFloorX() === $this->start_position->getFloorX() and
            $player->getPosition()->getFloorY() === $this->start_position->getFloorY() and
            $player->getPosition()->getFloorZ() === $this->start_position->getFloorZ()) {
            $player->sendTip(WarpDelay::getConfigReplace("warp_msg_cooldown", ["{time}"], [$this->timer]));
            $this->timer--;
        } else {
            $player->sendMessage(WarpDelay::getConfigReplace("warp_msg_cancel"));
            $player->getEffects()->remove(VanillaEffects::BLINDNESS());
            $this->getHandler()->cancel();
            return;
        }

        if ($this->timer === 0) {
            $player->getEffects()->remove(VanillaEffects::BLINDNESS());
            $player->teleport(WarpAPI::getWarp($this->warp));
            $player->sendTip(WarpDelay::getConfigReplace("warp_msg_teleport"));
            $this->getHandler()->cancel();
            return;
        }
    }
}