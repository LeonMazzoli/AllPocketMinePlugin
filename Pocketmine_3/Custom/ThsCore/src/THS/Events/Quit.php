<?php

namespace THS\Events;

use pocketmine\event\Listener;
use pocketmine\event\player\PlayerQuitEvent;
use THS\API\DiscordAPI;
use THS\API\QuitAPI;

class Quit implements Listener{
    public function onQuit(PlayerQuitEvent $event){
        $player = $event->getPlayer();
        DiscordAPI::sendMessage("**{$player->getName()}** --");
        QuitAPI::message($event, $player);
        foreach ($player->getInventory()->getContents() as $itemclear) {
            $notClear = ["438:16", "438:29", "438:33", "466:0", "378:0"];
            if (!in_array($itemclear->getId() . ":" . $itemclear->getDamage(), $notClear)){
                $player->getInventory()->removeItem($itemclear);
            }
        }
        $player->getArmorInventory()->clearAll();
    }
}