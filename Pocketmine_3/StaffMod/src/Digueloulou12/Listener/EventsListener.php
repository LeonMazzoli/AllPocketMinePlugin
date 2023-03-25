<?php

namespace Digueloulou12\Listener;

use Digueloulou12\API\StaffAPI;
use Digueloulou12\Forms\StaffForms;
use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\event\player\PlayerQuitEvent;
use pocketmine\event\player\PlayerTransferEvent;
use pocketmine\Player;

class EventsListener implements Listener{
    public function onJoin(PlayerJoinEvent $event){
        if (!empty(StaffAPI::$freeze[$event->getPlayer()->getName()])){
            $event->getPlayer()->setImmobile(true);
        }
    }

    public function onUse(PlayerInteractEvent $event){
        $item = explode(":", StaffAPI::getConfigValue("item"));

        if (!StaffAPI::isStaffMod($event->getPlayer())) return;

        if ($event->getItem()->getId().":".$event->getItem()->getDamage() === $item[0].":".$item[1]){
            StaffForms::formMain($event->getPlayer());
            $event->setCancelled(true);
        }
    }

    public function onTap(EntityDamageByEntityEvent $event){
        $player = $event->getDamager();
        $sender = $event->getEntity();

        if (!($sender instanceof Player) or !($player instanceof Player)) return;

        if (StaffAPI::isStaffMod($player)){
            StaffForms::form($player, $sender);
            $event->setCancelled(true);
        }
    }

    public function onTransfer(PlayerTransferEvent $event){
        if (StaffAPI::isStaffMod($event->getPlayer())){
            $event->getPlayer()->getInventory()->setContents(StaffAPI::$staff[$event->getPlayer()->getName()]["inv"]);
            $event->getPlayer()->getArmorInventory()->setContents(StaffAPI::$staff[$event->getPlayer()->getName()]["armor"]);
            unset(StaffAPI::$staff[$event->getPlayer()->getName()]);
        }
    }

    public function onQuit(PlayerQuitEvent $event){
        if (StaffAPI::isStaffMod($event->getPlayer())){
            $event->getPlayer()->getInventory()->setContents(StaffAPI::$staff[$event->getPlayer()->getName()]["inv"]);
            $event->getPlayer()->getArmorInventory()->setContents(StaffAPI::$staff[$event->getPlayer()->getName()]["armor"]);
            unset(StaffAPI::$staff[$event->getPlayer()->getName()]);
        }
    }
}