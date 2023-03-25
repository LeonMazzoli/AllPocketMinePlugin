<?php

namespace THS\Events;

use pocketmine\event\Listener;
use pocketmine\event\player\PlayerChatEvent;
use pocketmine\Server;
use pocketmine\utils\Config;
use THS\API\DiscordAPI;
use THS\API\LanguageAPI;
use THS\Main;

class Chat implements Listener{
    private $word = ["bvn", "bienvenue", "bvn!", "bienvenue!"];
    public static $mute = [];
    public function onChat(PlayerChatEvent $event){
        $language = new Config(Main::getInstance()->getDataFolder()."language.json",Config::JSON);
        $player = $event->getPlayer();
        $message = $event->getMessage();


        if (isset(self::$mute[$player->getName()])){
            if (self::$mute[$player->getName()] > time()){
                $event->setCancelled(true);
                LanguageAPI::sendMessage($player, "Vous ne pouvez pas envoyé de message quand vous etes mute !", "You cannot send a message when you are muted!");
                return;
            }
        }

        if (in_array(strtolower($message), $this->word)){
            $event->setCancelled(true);
            foreach (Server::getInstance()->getOnlinePlayers() as $sender){
                if ($language->get(strtolower($sender->getName())) === "en"){
                    $sender->sendMessage(Main::$prefix."{$event->getPlayer()->getName()} welcome you to the server!");
                }else $sender->sendMessage(Main::$prefix."{$event->getPlayer()->getName()} vous dit la bienvenue sur le serveur !");
            }
        }

        if (!$event->isCancelled()){
            DiscordAPI::sendMessage($player->getName()." -> " . $event->getMessage());
        }
    }
}