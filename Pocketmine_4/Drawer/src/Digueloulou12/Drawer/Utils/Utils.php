<?php

namespace Digueloulou12\Drawer\Utils;

use Digueloulou12\Drawer\Drawer;
use JetBrains\PhpStorm\Pure;
use pocketmine\block\Block;
use pocketmine\console\ConsoleCommandSender;
use pocketmine\player\Player;

class Utils
{
    public static function getConfigValue(string $path, bool $nested = false): mixed
    {
        return $nested ? Drawer::getInstance()->getConfig()->getNested($path) : Drawer::getInstance()->getConfig()->get($path);
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