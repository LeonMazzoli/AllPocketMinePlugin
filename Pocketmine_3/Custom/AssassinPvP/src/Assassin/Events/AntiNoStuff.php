<?php

namespace Assassin\Events;

use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\event\Listener;
use pocketmine\Player;

class AntiNoStuff implements Listener {
    public function entirydamage(EntityDamageByEntityEvent $event){
        $player = $event->getEntity();
        $sender = $event->getDamager();

        if (!($player instanceof Player) or !($sender instanceof Player)) return;
        if ($player->getLevel()->getName() === "Laser-0" or
            $player->getLevel()->getName() === "Laser-1" or
            $player->getLevel()->getName() === "Laser-2" or
            $player->getLevel()->getName() === "Laser-3" or
            $player->getLevel()->getName() === "Laser-melee" or
            $player->getLevel()->getName() === "snowpvp" or
            $player->getLevel()->getName() === "arcpvp" or
            $player->getLevel()->getName() === "BuildAssassin"
        ) return;
        if ((!$player->getArmorInventory()->getHelmet()->isNull() or
            !$player->getArmorInventory()->getChestplate()->isNull() or
            !$player->getArmorInventory()->getLeggings()->isNull() or
            !$player->getArmorInventory()->getBoots()->isNull())
            and
            (!$sender->getArmorInventory()->getHelmet()->isNull() or
            !$sender->getArmorInventory()->getChestplate()->isNull() or
            !$sender->getArmorInventory()->getLeggings()->isNull() or
            !$sender->getArmorInventory()->getBoots()->isNull())
        ){
            $event->setCancelled(false);
            return;
        }
        if ($player->getArmorInventory()->getHelmet()->isNull() and
            $player->getArmorInventory()->getChestplate()->isNull() and
            $player->getArmorInventory()->getLeggings()->isNull() and
            $player->getArmorInventory()->getBoots()->isNull()){
            $event->setCancelled(true);
            return;
        }
        if (!$sender->getArmorInventory()->getHelmet()->isNull() and
            !$sender->getArmorInventory()->getChestplate()->isNull() and
            !$sender->getArmorInventory()->getLeggings()->isNull() and
            !$sender->getArmorInventory()->getBoots()->isNull()){
            $event->setCancelled(true);
            return;
        }
    }
}