<?php

namespace THS\Events;

use pocketmine\event\Listener;
use pocketmine\event\player\PlayerCommandPreprocessEvent;
use pocketmine\event\server\CommandEvent;
use THS\API\DiscordAPI;
use THS\Main;

class PlayerCommand implements Listener{
    public static $spy = [];
    public function onChat(PlayerCommandPreprocessEvent $event){
        $player = $event->getPlayer();
        $message = $event->getMessage();

        if ($message[0] === "/"){
            Main::getInstance()->getLogger()->info('§f» §a' . $player->getName() . ' §7->§a ' . $message);
            DiscordAPI::sendMessage('§f» §a' . $player->getName() . ' §7->§a ' . $message);
        }

        if (!empty(self::$spy)){
            foreach (self::$spy as $espion){
                if ($message[0] === "/"){
                    $espion->sendMessage('§f» §a' . $player->getName() . ' §7->§a ' . $message);
                }
            }
        }
    }

    // public function onUseCommand(CommandEvent $event) {
    //     $cmd = $event->getCommand();
    //     $command = explode(" ", $cmd);
    //     $event->setCommand(strtolower($cmd));
    // }
}