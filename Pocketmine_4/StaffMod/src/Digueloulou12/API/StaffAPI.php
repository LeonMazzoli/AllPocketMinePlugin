<?php

namespace Digueloulou12\API;

use Digueloulou12\MainStaff;
use pocketmine\player\Player;
use pocketmine\utils\Config;

class StaffAPI
{
    public static array $staff = [];
    public static array $freeze = [];

    public static function isStaffMod(Player $player): bool
    {
        return isset(self::$staff[$player->getName()]);
    }

    public static function getConfigValue(string $value): mixed
    {
        $config = new Config(MainStaff::getInstance()->getDataFolder() . "config.yml", Config::YAML);
        return $config->get($value);
    }

    public static function sendMessage(Player $player, string $msg, $replace = [], $replacer = []): void
    {
        $player->sendMessage(self::getConfigValue("prefix") . str_replace($replace, $replacer, self::getConfigValue($msg)));
    }
}