<?php

namespace Digueloulou12\API;

use Digueloulou12\Main;
use pocketmine\level\Position;
use pocketmine\Player;
use pocketmine\Server;
use pocketmine\utils\Config;

class SkyblockAPI
{
    public static Config $config;
    public static $invitation = [];

    public function __construct()
    {
        self::$config = new Config(Main::getInstance()->getDataFolder() . "skyblock.json", Config::JSON);
    }

    public static function isInIsland($player): bool
    {
        if ($player instanceof Player) {
            $name = $player->getName();
        } else $name = $player;

        $return = false;
        foreach (self::$config->getAll() as $island => $key) {
            if (self::$config->getNested("$island.Members.$name") !== null) {
                $return = true;
            }
        }
        return $return;
    }

    public static function createIsland($player, string $name_is)
    {
        if ($player instanceof Player) {
            $name = $player->getName();
        } else $name = $player;

        WorldAPI::duplicateLevel(Main::getConfigAPI()->getConfigValue("is_starter"), $name_is);

        Server::getInstance()->loadLevel($name_is);

        $level = Server::getInstance()->getLevelByName($name_is);

        $info = [
            "Owner" => $name,
            "Members" => [$name => "Owner"],
            "Bank" => 0,
            "Lock" => false,
            "Spawn" => [$level->getSpawnLocation()->getX(), $level->getSpawnLocation()->getY(), $level->getSpawnLocation()->getZ()]
        ];

        self::$config->set($name_is, $info);
        self::$config->save();
    }

    public static function disbandIsland(string $name_is)
    {
        $player = self::getOwnerPlayer($name_is);
        MoneyAPI::addMoney($player, self::islandBank($name_is));
        self::$config->remove($name_is);
        self::$config->save();
        WorldAPI::removeLevel($name_is);
    }

    public static function getOwnerPlayer(string $island): string
    {
        return self::$config->get($island)["Owner"];
    }

    public static function getIslandPlayer($player)
    {
        if ($player instanceof Player) {
            $name = $player->getName();
        } else $name = $player;

        $return = null;
        foreach (self::$config->getAll() as $island => $key) {
            if ($key["Members"][$name] !== null) {
                $return = $island;
            }
        }
        return $return;
    }

    public static function islandBank(string $name_is, int $cost = 0, string $type = "get")
    {
        switch ($type) {
            case "get":
                return self::$config->get($name_is)["Bank"];
                break;
            case "add":
                self::$config->setNested("$name_is.Bank", self::islandBank($name_is) + $cost);
                break;
            case "remove":
                self::$config->setNested("$name_is.Bank", self::islandBank($name_is) - $cost);
                break;
        }
        self::$config->save();
        return true;
    }

    public static function getSpawnIsland(string $name_is): Position
    {
        $pos = self::$config->get($name_is)["Spawn"];
        return new Position($pos[0], $pos[1], $pos[2], Server::getInstance()->getLevelByName($name_is));
    }

    public static function setSpawnIsland(Player $player, string $name_is)
    {
        self::$config->setNested("$name_is.Spawn", [$player->getX(), $player->getY(), $player->getZ()]);
        self::$config->save();
    }

    public static function getRankPlayer($player): string
    {
        if ($player instanceof Player) $name = $player->getName(); else $name = $player;

        $name_is = self::getIslandPlayer($player);
        return self::$config->get($name_is)["Members"][$name];
    }

    public static function getAllMembers(string $name_is): array
    {
        $array = [];
        foreach (self::$config->get($name_is)["Members"] as $membre => $value) {
            $array[] = $membre;
        }
        return $array;
    }

    public static function removePlayerIsland($player, string $name_is)
    {
        if ($player instanceof Player) $name = $player->getName(); else $name = $player;
        self::$config->removeNested("$name_is.Members.$name");
        self::$config->save();
    }

    public static function setOwnerIsland($player, string $name_is)
    {
        if ($player instanceof Player) $name = $player->getName(); else $name = $player;

        $owner = self::getOwnerPlayer($name_is);

        self::$config->setNested("$name_is.Owner", $name);
        self::$config->setNested("$name_is.Members.$name", "Owner");
        self::$config->setNested("$name_is.Members.$owner", "Officier");
        self::$config->save();
    }

    public static function addMemberIsland($player, string $name_is)
    {
        if ($player instanceof Player) $name = $player->getName(); else $name = $player;

        self::$config->setNested("$name_is.Members.$name", "Membre");
        self::$config->save();
    }
}