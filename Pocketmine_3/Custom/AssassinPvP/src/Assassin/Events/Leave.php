<?php

namespace Assassin\Events;

use Assassin\Main;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerQuitEvent;
use pocketmine\Server;

class Leave implements Listener{
    public function onLeave(PlayerQuitEvent $event){
        $player = $event->getPlayer();
        $event->setQuitMessage("");

        Server::getInstance()->broadcastMessage(Main::$prefix . "§a{$player->getName()} §fvient de quitter le serveur");
    }
}