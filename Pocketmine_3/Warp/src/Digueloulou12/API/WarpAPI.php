<?php

namespace Digueloulou12\API;

use pocketmine\level\Position;
use pocketmine\utils\Config;
use Digueloulou12\Warp;
use pocketmine\Player;
use pocketmine\Server;

class WarpAPI
{
    public static function getAllWarps(): array
    {
        $warp = new Config(Warp::getInstance()->getDataFolder() . "warps.json", Config::JSON);
        $warps = [];
        foreach ($warp->getAll() as $warp_ => $pos) {
            $warps[] = $warp_;
        }
        return $warps;
    }

    public static function existWarp(string $name): bool
    {
        return in_array($name, self::getAllWarps());
    }

    public static function addWarp(Player $pos, string $name)
    {
        $warp = new Config(Warp::getInstance()->getDataFolder() . "warps.json", Config::JSON);
        $warp->set($name, [$pos->getPosition()->getX(), $pos->getPosition()->getY(), $pos->getPosition()->getZ(), $pos->getLevel()->getName()]);
        $warp->save();
    }

    public static function getWarp(string $name): Position
    {
        $warp = new Config(Warp::getInstance()->getDataFolder() . "warps.json", Config::JSON);
        $pos = $warp->get($name);
        return new Position($pos[0], $pos[1], $pos[2], Server::getInstance()->getLevelByName($pos[3]));
    }

    public static function delWarp(string $name)
    {
        $warp = new Config(Warp::getInstance()->getDataFolder() . "warps.json", Config::JSON);
        $warp->remove($name);
        $warp->save();
    }
}