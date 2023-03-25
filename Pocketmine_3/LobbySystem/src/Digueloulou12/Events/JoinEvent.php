<?php

namespace Digueloulou12\Events;

use Digueloulou12\LobbyMain;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\item\Item;

class JoinEvent implements Listener{
    public function onJoin(PlayerJoinEvent $event){
        $item = explode(":", LobbyMain::getConfigValue("item"));

        $itemm = Item::get($item[0], $item[1], 1);
        if (isset($item[3])) $itemm->setCustomName($item[3]);

        $event->getPlayer()->getInventory()->setItem($item[2], $itemm);

        if (LobbyMain::getConfigValue("fly") === true){
            if (LobbyMain::getConfigValue("op") === true){
                if ($event->getPlayer()->isOp()){
                    $event->getPlayer()->setFlying(true);
                    $event->getPlayer()->setAllowFlight(true);
                }
            }else{
                $event->getPlayer()->setFlying(true);
                $event->getPlayer()->setAllowFlight(true);
            }
        }
    }
}