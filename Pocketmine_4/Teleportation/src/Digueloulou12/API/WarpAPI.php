<?php

namespace Digueloulou12\API;

use Digueloulou12\Teleportation;
use pocketmine\player\Player;
use pocketmine\Server;
use pocketmine\utils\Config;
use pocketmine\world\Position;

class WarpAPI
{
    public static function getAllWarps(): array
    {
        $warp = new Config(Teleportation::getInstance()->getDataFolder() . "warps.json", Config::JSON);
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
        $warp = new Config(Teleportation::getInstance()->getDataFolder() . "warps.json", Config::JSON);
        $warp->set($name, [$pos->getPosition()->getX(), $pos->getPosition()->getY(), $pos->getPosition()->getZ(), $pos->getWorld()->getFolderName()]);
        $warp->save();
    }

    public static function getWarp(string $name): Position
    {
        $warp = new Config(Teleportation::getInstance()->getDataFolder() . "warps.json", Config::JSON);
        $pos = $warp->get($name);
        return new Position($pos[0], $pos[1], $pos[2], Server::getInstance()->getWorldManager()->getWorldByName($pos[3]));
    }

    public static function delWarp(string $name)
    {
        $warp = new Config(Teleportation::getInstance()->getDataFolder() . "warps.json", Config::JSON);
        $warp->remove($name);
        $warp->save();
    }
}