<?php

namespace Digueloulou12;

use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\event\Listener;
use pocketmine\Player;

class ClanEvent implements Listener{
    public function onDamage(EntityDamageByEntityEvent $event){
        $player = $event->getEntity();
        $sender = $event->getDamager();
        if (!($player instanceof Player) or !($sender instanceof Player)){
            return;
        }

        if (!ClanAPI::playerExistInClan($player) or !ClanAPI::playerExistInClan($sender)){
            return;
        }

        if (ClanAPI::getClanOfPlayer($player) === ClanAPI::getClanOfPlayer($sender)){
            $event->setCancelled(true);
        }
    }
}