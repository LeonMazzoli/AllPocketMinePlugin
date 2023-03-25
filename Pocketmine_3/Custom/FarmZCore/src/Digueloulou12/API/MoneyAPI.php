<?php

namespace Digueloulou12\API;

use Digueloulou12\Main;
use pocketmine\Player;

class MoneyAPI
{
    public static function existPlayer($player): bool{
        if ($player instanceof Player) $name = $player->getName(); else $name = $player;
        return Main::$players->exists($name);
    }

    public static function getMoney($player): int
    {
        if ($player instanceof Player) $name = $player->getName(); else $name = $player;
        return Main::$players->get($name)["Money"];
    }

    public static function addMoney($player, int $money){
        if ($player instanceof Player) $name = $player->getName(); else $name = $player;
        Main::$players->setNested("$name.Money", self::getMoney($player) + $money);
        Main::$players->save();
    }

    public static function removeMoney($player, int $money){
        if ($player instanceof Player) $name = $player->getName(); else $name = $player;
        Main::$players->setNested("$name.Money", self::getMoney($player) - $money);
        Main::$players->save();
    }

    public static function setMoney($player, int $money){
        if ($player instanceof Player) $name = $player->getName(); else $name = $player;
        Main::$players->setNested("$name.Money", $money);
        Main::$players->save();
    }
}