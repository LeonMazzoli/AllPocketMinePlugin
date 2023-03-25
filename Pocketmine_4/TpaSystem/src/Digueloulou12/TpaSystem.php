<?php

namespace Digueloulou12;

use Digueloulou12\Commands\Tpa;
use Digueloulou12\Commands\Tpaccept;
use Digueloulou12\Commands\Tpahere;
use Digueloulou12\Events\TpaDamageEvents;
use pocketmine\player\Player;
use pocketmine\plugin\PluginBase;

class TpaSystem extends PluginBase
{
    private static TpaSystem $tpaSystem;
    public static array $players = [];

    public function onEnable(): void
    {
        self::$tpaSystem = $this;
        $this->saveDefaultConfig();

        $this->getServer()->getCommandMap()->registerAll("TpaSystemCommands",
            [
                new Tpa(), new Tpahere(), new Tpaccept()
            ]
        );

        $this->getServer()->getPluginManager()->registerEvents(new TpaDamageEvents(), $this);
    }

    public static function getInstance(): TpaSystem
    {
        return self::$tpaSystem;
    }

    public static function getConfigReplace(string $path, array $replace = [], array $replacer = []): string
    {
        $return = str_replace("{prefix}", self::$tpaSystem->getConfig()->get("prefix"), self::$tpaSystem->getConfig()->get($path));
        return str_replace($replace, $replacer, $return);
    }

    public static function hasPermissionPlayer(Player $player, string $perm): bool
    {
        if (self::getInstance()->getServer()->isOp($player->getName())) return false;
        if ($player->hasPermission($perm)) return false; else $player->sendMessage(self::getConfigReplace("no_perm"));
        return true;
    }

    public static function getPlayerName($player): string
    {
        if ($player instanceof Player) return $player->getName(); else return $player;
    }

    public static function getConfigValue(string $path): mixed
    {
        return self::$tpaSystem->getConfig()->get($path);
    }
}