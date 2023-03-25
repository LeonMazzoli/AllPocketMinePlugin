<?php

namespace Digueloulou12\Events;

use Digueloulou12\LobbyForm;
use Digueloulou12\LobbyMain;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerDropItemEvent;
use pocketmine\event\player\PlayerInteractEvent;

class InteractEvent implements Listener
{
    public function onUse(PlayerInteractEvent $event)
    {
        $item = explode(":", LobbyMain::getConfigValue("item"));

        if ($event->getItem()->getId() . ":" . $event->getItem()->getDamage() === $item[0] . ":" . $item[1]) {
            LobbyForm::form($event->getPlayer());
            return;
        }

        if (LobbyMain::getConfigValue("use") === false) {
            if (LobbyMain::getConfigValue("op") === false) {
                $event->setCancelled(true);
            } elseif (!$event->getPlayer()->isOp()) $event->setCancelled(true);
        }
    }

    public function onDrop(PlayerDropItemEvent $event){
        if (LobbyMain::getConfigValue("drop") === false){
            if (LobbyMain::getConfigValue("op") === false){
                $event->setCancelled(true);
            }else{
                if (!$event->getPlayer()->isOp()){
                    $event->setCancelled(true);
                }
            }
        }
    }
}