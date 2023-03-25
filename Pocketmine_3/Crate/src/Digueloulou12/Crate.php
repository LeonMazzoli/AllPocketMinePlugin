<?php

namespace Digueloulou12;

use Digueloulou12\Command\CrateCommand;
use Digueloulou12\Event\CrateEvent;
use pocketmine\plugin\PluginBase;
use pocketmine\Player;

class Crate extends PluginBase
{
    private static Crate $main;

    public function onEnable()
    {
        self::$main = $this;
        $this->saveDefaultConfig();
        $this->getServer()->getCommandMap()->register("", new CrateCommand());
        $this->getServer()->getPluginManager()->registerEvents(new CrateEvent(), $this);
    }

    public static function getInstance(): Crate
    {
        return self::$main;
    }

    public static function getConfigReplace(string $path, array $replace = [], array $replacer = []): string
    {
        $return = str_replace("{prefix}", self::$main->getConfig()->get("prefix"), self::$main->getConfig()->get($path));
        return str_replace($replace, $replacer, $return);
    }

    public static function hasPermissionPlayer(Player $player, string $perm): bool
    {
        if ($player->hasPermission($perm)) return false; else $player->sendMessage(self::getConfigReplace("no_perm"));
        return true;
    }

    public static function getConfigValue(string $path): array|int|string|bool
    {
        return self::$main->getConfig()->get($path);
    }
}