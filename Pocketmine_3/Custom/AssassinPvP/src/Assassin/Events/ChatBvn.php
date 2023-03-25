<?php

namespace Assassin\Events;

use Assassin\Main;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerChatEvent;
use pocketmine\Server;

class ChatBvn implements Listener{
    public function onChat(PlayerChatEvent $event){
        $message = $event->getMessage();
        if ($event->isCancelled()) return;
        $msg = strtolower("bvn bienvenue Bienvenue! bvn!");
        $msgs = explode(" ", $msg);
        if ($message === $msgs[0] or $message === $msgs[1] or $message === strtolower("Bienvenue !") or $message === strtolower("bvn !") or $message === $msgs[2] or $message === $msgs[3]){
            $event->setCancelled(true);
            Server::getInstance()->broadcastMessage(Main::$prefix . "§a" . $event->getPlayer()->getName() . " §fvous souhaite la bienvenue sur Assassin et bon amusement !!!");
        }
    }
}