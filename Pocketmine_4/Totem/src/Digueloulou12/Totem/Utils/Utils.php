<?php

namespace Digueloulou12\Totem\Utils;

use Digueloulou12\Totem\Totem;
use pocketmine\command\CommandSender;
use pocketmine\console\ConsoleCommandSender;
use pocketmine\player\Player;
use pocketmine\Server;

class Utils
{
    public static function getConfigValue(string $path, bool $nested = false): mixed
    {
        return $nested ? Totem::getInstance()->getConfig()->getNested($path) : Totem::getInstance()->getConfig()->get($path);
    }

    public static function getConfigReplace(string $path, array|string $re = [], array|string $r = [], bool $nested = false): string
    {
        return str_replace("{prefix}", self::getConfigValue("prefix"), str_replace($re, $r, self::getConfigValue($path, $nested)));
    }

    public static function getPlayerName(mixed $player): mixed
    {
        if ($player instanceof Player) {
            return $player->getName();
        } elseif ($player instanceof ConsoleCommandSender) {
            return "Server";
        } else return $player;
    }

    public static function hasPermissionPlayer(CommandSender $player, string $command, bool $perm = false): bool
    {
        if (Server::getInstance()->isOp($player->getName())) return true;

        if (!$perm) {
            if (isset(self::getConfigValue($command)[2])) {
                $p = self::getConfigValue($command)[2];
            } else return true;
        } else $p = $command;

        if ($player instanceof Player) {
            if (!$player->hasPermission($p)) {
                $player->sendMessage(self::getConfigReplace("no_perm"));
                return false;
            } else return true;
        } else return true;
    }
}