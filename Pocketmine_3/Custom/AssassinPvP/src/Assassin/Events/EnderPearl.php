<?php

namespace Assassin\Events;

use pocketmine\event\Listener;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\Player;

class EnderPearl implements Listener
{
    public $cooldown = [];
    public function onInter(PlayerInteractEvent $event)
    {
        $player = $event->getPlayer();
        $item = $event->getItem();
        if ($item->getId() === 368){
            if ($event->getAction() === 1 or $event->getAction() === 3){
                if (empty($this->cooldown[$player->getName()]) or $this->cooldown[$player->getName()] < time()){
                    $this->cooldown[$player->getName()] = time() + 10;
                }else{
                    $event->setCancelled(true);
                    $temps = $this->cooldown[$player->getName()] - time();
                    $player->sendTip("§a" . $temps . "§f seconde(s)");
                }
            }
        }
    }
}