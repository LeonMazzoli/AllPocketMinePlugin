<?php

namespace Digueloulou12\API;

use Digueloulou12\MainStaff;
use pocketmine\Player;
use pocketmine\utils\Config;

class StaffAPI{
    public static $staff = [];
    public static $freeze = [];
    public static function isStaffMod(Player $player){
        return isset(self::$staff[$player->getName()]);
    }

    public static function getConfigValue(string $value){
        $config = new Config(MainStaff::getInstance()->getDataFolder()."config.yml",Config::YAML);
        return $config->get($value);
    }

    public static function sendMessage(Player $player, string $msg, $replace = [], $replacer = []){
        $player->sendMessage(self::getConfigValue("prefix").str_replace($replace, $replacer, self::getConfigValue($msg)));
    }
}