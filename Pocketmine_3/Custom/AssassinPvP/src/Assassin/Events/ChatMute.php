<?php

namespace Assassin\Events;

use Assassin\Commands\Mute;
use Assassin\Main;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerChatEvent;

class ChatMute implements Listener {
    public function onChat(PlayerChatEvent $event) : void {
        $player = $event->getPlayer();
        if (isset(Mute::$mute[$player->getName()])) {
            if (Mute::$mute[$player->getName()] > time()) {
                $event->setCancelled();
                $time = Mute::$mute[$player->getName()] - time();
                $player->sendMessage(Main::$prefix."Vous ne pouvez pas parler parce que vous êtes mute pendant encore§a $time §fseconde(s) !");
            }
        }
    }
}