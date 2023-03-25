<?php

namespace THS\Events;

use pocketmine\event\Listener;
use pocketmine\event\player\PlayerJoinEvent;
use THS\API\DiscordAPI;
use THS\API\JoinAPI;

class Join implements Listener{
    public function onJoin(PlayerJoinEvent $event){
        $player = $event->getPlayer();
        JoinAPI::money($player);
        JoinAPI::message($event, $player);
        JoinAPI::divers($player);
        JoinAPI::xp($player);
        DiscordAPI::sendMessage("**{$player->getName()}** ++");
    }
}