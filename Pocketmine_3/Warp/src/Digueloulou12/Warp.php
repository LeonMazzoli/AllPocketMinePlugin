<?php

namespace Digueloulou12;

use Digueloulou12\Commands\DelWarp;
use Digueloulou12\Commands\SetWarp;
use pocketmine\Player;
use pocketmine\plugin\PluginBase;

class Warp extends PluginBase
{
    private static Warp $warp;

    public function onEnable()
    {
        self::$warp = $this;
        $this->saveResource("config.yml");

        $command = $this->getServer()->getCommandMap();
        $command->register("", new \Digueloulou12\Commands\Warp());
        $command->register("", new SetWarp());
        $command->register("", new DelWarp());
    }

    public static function getInstance(): Warp
    {
        return self::$warp;
    }

    public static function getConfigReplace(string $path, array $replace = [], array $replacer = []): string
    {
        $return = str_replace("{prefix}", self::$warp->getConfig()->get("prefix"), self::$warp->getConfig()->get($path));
        return str_replace($replace, $replacer, $return);
    }

    public static function hasPermissionPlayer(Player $player, string $perm): bool
    {
        if ($player->hasPermission($perm)) return false; else $player->sendMessage(self::getConfigReplace("no_perm"));
        return true;
    }

    public static function getConfigValue(string $path): array|int|string|bool
    {
        return self::$warp->getConfig()->get($path);
    }
}