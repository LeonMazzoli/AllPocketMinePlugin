<?php

namespace Digueloulou12\API;

use Digueloulou12\Main;
use pocketmine\level\Position;
use pocketmine\Player;
use pocketmine\Server;
use pocketmine\utils\Config;

class WarpAPI
{
    public static function addWarp(Player $player, string $name)
    {
        $warp = new Config(Main::getInstance()->getDataFolder() . "warps.json", Config::JSON);
        $warp->set($name, [$player->getX(), $player->getY(), $player->getZ(), $player->getLevel()->getName()]);
        $warp->save();
    }

    public static function removeWarp(string $name)
    {
        $warp = new Config(Main::getInstance()->getDataFolder() . "warps.json", Config::JSON);
        $warp->remove($name);
        $warp->save();
    }

    public static function existWarp(string $name): bool
    {
        $warp = new Config(Main::getInstance()->getDataFolder() . "warps.json", Config::JSON);
        return $warp->exists($name);
    }

    public static function teleportToWarp(Player $player, string $name){
        $warp = new Config(Main::getInstance()->getDataFolder() . "warps.json", Config::JSON);
        $pos = $warp->get($name);

        if (!Server::getInstance()->isLevelLoaded($pos[3])){
            Server::getInstance()->loadLevel($pos[3]);
        }

        $player->teleport(new Position($pos[0], $pos[1], $pos[2], Server::getInstance()->getLevelByName($pos[3])));
        $player->sendMessage(Main::getConfigAPI()->getConfigValue("warp_teleport", ["{warp}"], [$name]));
    }

    public static function listWarps(): array
    {
        $warp = new Config(Main::getInstance()->getDataFolder() . "warps.json", Config::JSON);
        $warps = [];

        foreach ($warp->getAll() as $name => $pos) {
            $warps[] = $name;
        }

        return $warps;
    }
}
