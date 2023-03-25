<?php

namespace Digueloulou12\API;

use Digueloulou12\Teleportation;
use pocketmine\player\Player;
use pocketmine\Server;
use pocketmine\utils\Config;
use pocketmine\world\Position;

class HomeAPI
{
    public static function createPlayer($player)
    {
        $home = new Config(Teleportation::getInstance()->getDataFolder() . "homes.json", Config::JSON);
        if (!self::existPlayer($player)) {
            $home->set(Teleportation::getPlayerName($player), []);
            $home->save();
        }
    }

    public static function existPlayer($player): bool
    {
        $home = new Config(Teleportation::getInstance()->getDataFolder() . "homes.json", Config::JSON);
        return $home->exists(Teleportation::getPlayerName($player));
    }

    public static function getAllHomes($player): array
    {
        $home = new Config(Teleportation::getInstance()->getDataFolder() . "homes.json", Config::JSON);
        $homes = [];
        foreach ($home->get(Teleportation::getPlayerName($player)) as $home => $pos) {
            $homes[] = $home;
        }
        return $homes;
    }

    public static function existHome($player, string $name): bool
    {
        return in_array($name, self::getAllHomes($player));
    }

    public static function setHome(Player $pos, $player, string $name)
    {
        $home = new Config(Teleportation::getInstance()->getDataFolder() . "homes.json", Config::JSON);
        $home->setNested(Teleportation::getPlayerName($player) . ".$name", [$pos->getPosition()->getX(), $pos->getPosition()->getY(), $pos->getPosition()->getZ(), $pos->getWorld()->getFolderName()]);
        $home->save();
    }

    public static function getHome($player, string $name): Position
    {
        $home = new Config(Teleportation::getInstance()->getDataFolder() . "homes.json", Config::JSON);
        $pos = $home->get(Teleportation::getPlayerName($player))[$name];
        return new Position($pos[0], $pos[1], $pos[2], Server::getInstance()->getWorldManager()->getWorldByName($pos[3]));
    }

    public static function delHome($player, string $name)
    {
        $home = new Config(Teleportation::getInstance()->getDataFolder() . "homes.json", Config::JSON);
        $home->removeNested(Teleportation::getPlayerName($player) . ".$name");
        $home->save();
    }
}