<?php

namespace Assassin\Events;

use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\event\Listener;
use pocketmine\item\Item;
use pocketmine\level\Position;
use pocketmine\Player;
use pocketmine\Server;

class SumoVoids implements Listener{
    public function onDamage(EntityDamageEvent $event){
        if ($event->getEntity()->getLastDamageCause() === EntityDamageEvent::CAUSE_VOID){
            if ($event->getEntity()->getLevel()->getName() === "SUMO"){
                $player = $event->getEntity();
                if ($player instanceof Player) {
                    $event->getEntity()->teleport(new Position(362, 2, 61, Server::getInstance()->getLevelByName("SUMO")));
                    $player->getInventory()->clearAll();
                    $player->getArmorInventory()->clearAll();
                    $player->getInventory()->setItem(4, Item::get(Item::GOLDEN_SWORD));
                }
            }
        }
    }
}