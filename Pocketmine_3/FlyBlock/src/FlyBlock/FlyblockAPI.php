<?php

namespace FlyBlock;

use pocketmine\item\Item;
use pocketmine\Player;
use pocketmine\utils\Config;

class FlyblockAPI{
    public static function getInventory(Player $player){
        $config = new Config(FlyMain::getInstance()->getDataFolder()."config.yml",Config::YAML);

        if ($config->get("inventory") === false) return true;
        $id = explode(":", $config->get("block"));
        if ($player->getInventory()->contains(Item::get($id[0], $id[1], 1))){
            return true;
        }else return false;
    }

    public static function removeInventory(Player $player){
        $config = new Config(FlyMain::getInstance()->getDataFolder()."config.yml",Config::YAML);

        if ($config->get("inventory") === false) return;
        $id = explode(":", $config->get("block"));
        $player->getInventory()->removeItem(Item::get($id[0], $id[1], 1));
    }
}