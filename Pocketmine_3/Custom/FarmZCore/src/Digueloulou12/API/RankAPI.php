<?php

namespace Digueloulou12\API;

use Digueloulou12\Commands\Rank\AddPermission;
use Digueloulou12\Commands\Rank\AddRank;
use Digueloulou12\Commands\Rank\RemoveRank;
use Digueloulou12\Main;
use pocketmine\Server;
use pocketmine\utils\Config;

class RankAPI
{
    public static Config $config;

    public function __construct()
    {
        self::$config = new Config(Main::getInstance()->getDataFolder() . "rank.yml", Config::YAML);

        if (Main::$config->get("addrankk") === true) Server::getInstance()->getCommandMap()->register("", new AddRank());
        if (Main::$config->get("removerankk") === true) Server::getInstance()->getCommandMap()->register("", new RemoveRank());
        if (Main::$config->get("addpermissionn") === true) Server::getInstance()->getCommandMap()->register("", new AddPermission());
    }

    public static function existRank(string $rank): bool
    {
        return self::$config->exists($rank);
    }

    public static function addRank(string $rank): void
    {
        self::$config->set($rank, []);
        self::$config->save();
    }

    public static function removeRank(string $rank): void
    {
        self::$config->remove($rank);
        self::$config->save();
    }

    public static function addPermission(string $rank, string $perm): void
    {
        $array = self::$config->get($rank);
        array_push($array, $perm);
        self::$config->set($rank, $array);
        self::$config->save();
    }

    public static function removePermission(string $rank, string $perm): void
    {
        $array = self::$config->get($rank);
        unset($array[$perm]);
        self::$config->set($rank, $array);
        self::$config->save();
    }
}