<?php

namespace Digueloulou12\Advantages\Utils;

use pocketmine\plugin\Plugin;

class MoneyUtils
{
    private static Plugin $economy;

    public static function setEconomy(Plugin $plugin): void
    {
        self::$economy = $plugin;
    }

    public static function addMoney(mixed $player, int|float $amount): void
    {
        if ((self::$economy->getName() === "EconomyAPI") or (self::$economy->getName() === "MoneyAPI")) {
            self::$economy->addMoney($player, $amount);
        }
    }

    public static function getMoney(mixed $player): int
    {
        if (self::$economy->getName() === "EconomyAPI") {
            return self::$economy->getMoney($player);
        } elseif (self::$economy->getName() === "MoneyAPI") {
            return self::$economy->getMoneyPlayer($player);
        }
        return 0;
    }

    public static function removeMoney(mixed $player, int|float $amount): void
    {
        if (self::$economy->getName() === "EconomyAPI") {
            self::$economy->reduceMoney($player, $amount);
        } elseif (self::$economy->getName() === "MoneyAPI") {
            self::$economy->removeMoney($player, $amount);
        }
    }
}