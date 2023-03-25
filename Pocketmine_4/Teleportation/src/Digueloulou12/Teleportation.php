<?php

namespace Digueloulou12;

use Digueloulou12\Commands\Homes\AdminDelHome;
use Digueloulou12\Commands\Homes\AdminSetHome;
use Digueloulou12\Commands\Homes\AdminHome;
use Digueloulou12\Commands\Homes\DelHome;
use Digueloulou12\Commands\Homes\SetHome;
use Digueloulou12\Commands\Warps\DelWarp;
use Digueloulou12\Commands\Warps\SetWarp;
use Digueloulou12\Commands\Tpa\Tpaccept;
use Digueloulou12\Commands\Tpa\Tpahere;
use Digueloulou12\Commands\Homes\Home;
use Digueloulou12\Commands\Warps\Warp;
use Digueloulou12\Commands\Tpa\Tpa;
use pocketmine\plugin\PluginBase;
use Digueloulou12\Events\Events;
use pocketmine\player\Player;

class Teleportation extends PluginBase
{
    private static Teleportation $main;

    public function onEnable(): void
    {
        self::$main = $this;
        $this->saveResource("config.yml");

        $command = $this->getServer()->getCommandMap();
        if (self::getConfigValue("home_system")) {
            $command->register("", new Home());
            $command->register("", new SetHome());
            $command->register("", new DelHome());
            $command->register("", new AdminHome());
            $command->register("", new AdminSetHome());
            $command->register("", new AdminDelHome());

            $this->getServer()->getPluginManager()->registerEvents(new Events(), $this);
        }

        if (self::getConfigValue("warp_system")) {
            $command->register("", new Warp());
            $command->register("", new SetWarp());
            $command->register("", new DelWarp());
        }

        if (self::getConfigValue("tpa_system")) {
            $command->register("", new Tpa());
            $command->register("", new Tpahere());
            $command->register("", new Tpaccept());
        }
    }

    public static function getInstance(): Teleportation
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

    public static function getPlayerName($player): string
    {
        if ($player instanceof Player) return $player->getName(); else return $player;
    }

    public static function getConfigValue(string $path): array|int|string|bool
    {
        return self::$main->getConfig()->get($path);
    }
}