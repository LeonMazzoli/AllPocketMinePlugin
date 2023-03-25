<?php

namespace Digueloulou12\Welcome\Utils;

use Digueloulou12\Welcome\Welcome;
use JetBrains\PhpStorm\Pure;
use pocketmine\console\ConsoleCommandSender;
use pocketmine\player\Player;

class Utils
{
    public static function getConfigValue(string $path, bool $nested = false): mixed
    {
        return $nested ? Welcome::getInstance()->getConfig()->getNested($path) : Welcome::getInstance()->getConfig()->get($path);
    }

    public static function getConfigReplace(string $path, array|string $re = [], array|string $r = [], bool $nested = false): string
    {
        return str_replace("{prefix}", self::getConfigValue("prefix"), str_replace($re, $r, self::getConfigValue($path, $nested)));
    }

    #[Pure] public static function getPlayerName(mixed $player): mixed
    {
        if ($player instanceof Player) {
            return $player->getName();
        } elseif ($player instanceof ConsoleCommandSender) {
            return "Server";
        } else return $player;
    }
}