<?php

namespace Assassin\Events;

use Assassin\ModePvP\Mode;
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\event\Listener;
use pocketmine\Player;

class Snow implements Listener{
    public function damage(EntityDamageEvent $event){
        $player = $event->getEntity();
        if ($player->getLevel()->getName() === "snowpvp"){
            if ($player instanceof Player){
                if ($player->getY() <= 30){
                    $event->setCancelled(true);
                    Mode::tpsnow($player);
                    Mode::snow($player);
                }
            }
        }elseif ($player->getLevel()->getName() === "arcpvp"){
            if ($player instanceof Player){
                if ($player->getY() <= 30){
                    $event->setCancelled(true);
                    Mode::tparc($player);
                    Mode::arc($player);
                }
            }
        }
    }
}