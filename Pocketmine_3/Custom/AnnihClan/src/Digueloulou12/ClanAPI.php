<?php

namespace Digueloulou12;

use pocketmine\Player;
use pocketmine\utils\Config;

class ClanAPI{
    public static function playerExistInClan(Player $player)
    {
        $data = new Config(MainTeam::getInstance()->getDataFolder() . "data.json", Config::JSON);
        $return = false;
        foreach ($data->get("clan") as $clan => $key) {
            if (in_array($player->getName(), $key["Members"])) {
                $return = true;
            }
        }
        return $return;
    }

    public static function getClanOfPlayer(Player $player){
        $data = new Config(MainTeam::getInstance()->getDataFolder() . "data.json", Config::JSON);

        $return = null;
        foreach ($data->get("clan") as $clan => $key){
            if (in_array($player->getName(), $key["Members"])){
                $return = $key["Name"];
            }
        }
        return $return;
    }

    public static function existClan(string $clann){
        $data = new Config(MainTeam::getInstance()->getDataFolder() . "data.json", Config::JSON);

        $return = false;
        foreach ($data->get("clan") as $clan => $key){
            if ($key["Name"] === $clann){
                $return = true;
            }
        }
        return $return;
    }
}