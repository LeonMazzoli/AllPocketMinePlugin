<?php

namespace THS\API;

use pocketmine\Player;
use pocketmine\utils\Config;
use THS\Main;

class MoneyAPI
{
    public static function exist($player)
    {
        $money = new Config(Main::getInstance()->getDataFolder() . "money.json", Config::JSON);

        if ($player instanceof Player) {
            $name = strtolower($player->getName());
        } else $name = strtolower($player);

        if ($money->exists($name)) {
            return true;
        } else return false;
    }

    public static function myMoney($player)
    {
        $money = new Config(Main::getInstance()->getDataFolder() . "money.json", Config::JSON);

        if ($player instanceof Player) {
            $name = strtolower($player->getName());
        } else $name = strtolower($player);

        return $money->get($name);
    }

    public static function addMoney($player, $amount)
    {
        $money = new Config(Main::getInstance()->getDataFolder() . "money.json", Config::JSON);

        if ($player instanceof Player) {
            $name = strtolower($player->getName());
        } else $name = strtolower($player);

        $money->set($name, $money->get($name) + $amount);
        $money->save();
    }

    public static function removeMoney($player, $amount)
    {
        $money = new Config(Main::getInstance()->getDataFolder() . "money.json", Config::JSON);

        if ($player instanceof Player) {
            $name = strtolower($player->getName());
        } else $name = strtolower($player);

        $money->set($name, $money->get($name) - $amount);
        $money->save();
    }

    public static function setMoney($player, $amount)
    {
        $money = new Config(Main::getInstance()->getDataFolder() . "money.json", Config::JSON);

        if ($player instanceof Player) {
            $name = strtolower($player->getName());
        } else $name = strtolower($player);

        $money->set($name, $amount);
        $money->save();
    }
}