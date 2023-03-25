<?php

namespace Assassin\Events;

use pocketmine\entity\Effect;
use pocketmine\entity\EffectInstance;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerMoveEvent;

class SpeedLobby implements Listener{
    public function onMove(PlayerMoveEvent $event)
    {
        $player = $event->getPlayer();
        if ($player->getLevel()->getName() === "Lobby") {
            $speed = new EffectInstance(Effect::getEffect(Effect::SPEED), 20 * 100000, 6, false);
            $player->addEffect($speed);
        } else {
            if ($player->hasEffect(1)) {
                if ($player->getEffect(1)->getAmplifier() === 6) {
                    $player->removeEffect(1);
                }
            }
        }
    }
}