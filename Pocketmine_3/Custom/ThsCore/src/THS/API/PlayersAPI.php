<?php

namespace THS\API;

use pocketmine\Player;
use pocketmine\utils\Config;
use THS\Main;

class PlayersAPI{
    public static function initPlayer($player){
        $players = new Config(Main::getInstance()->getDataFolder()."players.json",Config::JSON);

        if ($player instanceof Player){
            $name = strtolower($player->getName());
        }else $name = strtolower($player);

        $config = [
            "boutique" => 0,
            "mute" => 0,
            "kick" => 0,
            "ban" => 0,
            "sprint" => false,
            "language" => "fr",
            "scoreboard" => true
        ];

        $players->set($name, $config);
        $players->save();
    }

    public static function existPlayer($player){
        $players = new Config(Main::getInstance()->getDataFolder()."players.json",Config::JSON);

        if ($player instanceof Player){
            $name = strtolower($player->getName());
        }else $name = strtolower($player);

        return $players->exists($name);
    }

    public static function getInfo($player, string $info){
        $players = new Config(Main::getInstance()->getDataFolder()."players.json",Config::JSON);

        if ($player instanceof Player){
            $name = strtolower($player->getName());
        }else $name = strtolower($player);

        return $players->getNested("$name.$info");
    }

    public static function setInfo($player, string $info, $content){
        $players = new Config(Main::getInstance()->getDataFolder()."players.json",Config::JSON);

        if ($player instanceof Player){
            $name = strtolower($player->getName());
        }else $name = strtolower($player);

        $players->setNested("$name.$info", $content);
        $players->save();
    }
}