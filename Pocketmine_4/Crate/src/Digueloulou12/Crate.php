<?php

namespace Digueloulou12;

use Digueloulou12\Command\CrateCommand;
use Digueloulou12\Event\CrateEvent;
use pocketmine\command\CommandSender;
use pocketmine\plugin\PluginBase;

class Crate extends PluginBase
{
    private static Crate $main;

    public function onEnable(): void
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

    public static function hasPermissionPlayer(CommandSender $player, string $perm): bool
    {
        if ($player->hasPermission($perm)) return false; else $player->sendMessage(self::getConfigReplace("no_perm"));
        return true;
    }

    public static function getConfigValue(string $path): mixed
    {
        return self::$main->getConfig()->get($path);
    }
}