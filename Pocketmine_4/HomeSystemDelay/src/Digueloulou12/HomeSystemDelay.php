<?php

namespace Digueloulou12;

use Digueloulou12\Commands\{AdminDelHome, AdminHome, AdminSetHome, DelHome, Home, SetHome};
use Digueloulou12\Events\HomeJoinEvent;
use pocketmine\plugin\PluginBase;
use Digueloulou12\API\HomeAPI;
use pocketmine\player\Player;

class HomeSystemDelay extends PluginBase
{
    private static HomeSystemDelay $homeSystemDelay;

    public function onEnable(): void
    {
        $this->saveDefaultConfig();

        self::$homeSystemDelay = $this;

        new HomeAPI();

        $this->getServer()->getCommandMap()->registerAll("HomeSystem", [
            new AdminDelHome(),
            new AdminHome(),
            new AdminSetHome(),
            new DelHome(),
            new Home(),
            new SetHome()
        ]);

        $this->getServer()->getPluginManager()->registerEvents(new HomeJoinEvent(), $this);
    }

    public static function getInstance(): HomeSystemDelay
    {
        return self::$homeSystemDelay;
    }

    public static function getConfigReplace(string $path, array $replace = [], array $replacer = []): string
    {
        $return = str_replace("{prefix}", self::$homeSystemDelay->getConfig()->get("prefix"), self::$homeSystemDelay->getConfig()->get($path));
        return str_replace($replace, $replacer, $return);
    }

    public static function getPlayerName($player): string
    {
        if ($player instanceof Player) return $player->getName(); else return $player;
    }

    public static function getConfigValue(string $path): mixed
    {
        return self::$homeSystemDelay->getConfig()->get($path);
    }
}