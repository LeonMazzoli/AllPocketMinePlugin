<?php

namespace THS\API;

use pocketmine\Player;
use pocketmine\Server;
use pocketmine\utils\Config;
use THS\Main;

class LanguageAPI{
    public static function sendMessage($player, $fr, $en, $type = "message"){
        if (!($player instanceof Player)){
            Main::getInstance()->getLogger()->info(Main::$prefix.$fr);
            return;
        }

        switch ($type){
            case "message":
                if (self::getLanguage($player) === "fr"){
                    $player->sendMessage(Main::$prefix.$fr);
                }else $player->sendMessage(Main::$prefix.$en);
                break;
            case "tip":
                if (self::getLanguage($player) === "fr"){
                    $player->sendTip($fr);
                }else $player->sendTip($en);
                break;
        }
    }

    public static function sendAllMessage($fr, $en, $type = "message"){
        foreach (Server::getInstance()->getOnlinePlayers() as $player){
            switch ($type){
                case "message":
                    if (self::getLanguage($player) === "fr"){
                        $player->sendMessage(Main::$prefix.$fr);
                    }else $player->sendMessage(Main::$prefix.$en);
                    break;
                case "tip":
                    if (self::getLanguage($player) === "fr"){
                        $player->sendTip(Main::$prefix.$fr);
                    }else $player->sendTip(Main::$prefix.$en);
                    break;
            }
        }
    }

    public static function getLanguage($player){
        $language = new Config(Main::getInstance()->getDataFolder() . "language.json", Config::JSON);

        if (!($player instanceof Player)){
            $lang = "fr";
        }

        if ($player instanceof Player){
            $name = strtolower($player->getName());
            $lang = $language->get($name);
        }
        return $lang;
    }
}