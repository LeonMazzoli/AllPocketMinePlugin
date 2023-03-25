<?php

namespace Assassin\Events;

use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\event\Listener;
use pocketmine\Player;

class Damage implements Listener{
    public function onTap(EntityDamageByEntityEvent $event){
        $player = $event->getEntity();
        $sender = $event->getDamager();

        if (!($player instanceof Player) or !($sender instanceof Player)) return;
        if ($sender->getInventory()->getItemInHand()->getId() === 283){ $event->setCancelled(true);}
        if ($player->getLevel()->getName() === "snowpvp" or $player->getLevel()->getName() === "arcpvp"){ $event->setCancelled(true);}
    }
}