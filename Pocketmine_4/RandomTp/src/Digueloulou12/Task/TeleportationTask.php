<?php

namespace Digueloulou12\Task;

use pocketmine\entity\effect\EffectInstance;
use pocketmine\entity\effect\VanillaEffects;
use pocketmine\scheduler\Task;
use pocketmine\world\Position;
use pocketmine\player\Player;
use Digueloulou12\RandomTp;

class TeleportationTask extends Task
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
        $this->timer = RandomTp::getInstance()->getConfig()->get("delay");
        RandomTp::getInstance()->getScheduler()->scheduleDelayedRepeatingTask($this, 20, 20);
    }

    public function onRun(): void
    {
        $player = $this->player;
        if (!$player->isOnline()) {
            $this->getHandler()->cancel();
            return;
        }

        $config = RandomTp::getInstance()->getConfig();
        if ($player->getPosition()->getFloorX() === $this->startposition->getFloorX() and
            $player->getPosition()->getFloorY() === $this->startposition->getFloorY() and
            $player->getPosition()->getFloorZ() === $this->startposition->getFloorZ()) {
            $player->sendTip(str_replace("{time}", $this->timer, $config->get("cooldown")));
            $this->timer--;
        } else {
            $player->sendMessage($config->get("no_rtp"));
            $player->getEffects()->remove(VanillaEffects::BLINDNESS());
            $this->getHandler()->cancel();
            return;
        }

        if ($this->timer === 0) {
            if ($config->get("resistance_effect")) $player->getEffects()->add(new EffectInstance(VanillaEffects::RESISTANCE(), 20 * 20, 10, false));
            $player->getEffects()->remove(VanillaEffects::BLINDNESS());
            $player->teleport($this->pos);
            $this->getHandler()->cancel();
            return;
        }
    }
}