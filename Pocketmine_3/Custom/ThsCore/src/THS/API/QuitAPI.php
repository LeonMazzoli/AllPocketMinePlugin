<?php

namespace THS\API;

use pocketmine\event\player\PlayerQuitEvent;
use pocketmine\Player;
use pocketmine\Server;

class QuitAPI{
    public static function message(PlayerQuitEvent $event, Player $player){
        $event->setQuitMessage("");
        Server::getInstance()->broadcastTip("§c- §f{$player->getName()} §c-");
    }
}