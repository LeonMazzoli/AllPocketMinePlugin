<?php

namespace Digueloulou12;

use Digueloulou12\Event\EnchantmentEvent;
use pocketmine\plugin\PluginBase;

class Table extends PluginBase
{
    private static Table $main;

    public function onEnable(): void
    {
        self::$main = $this;
        $this->saveDefaultConfig();

        $eco = $this->getConfig()->get("economy");
        if ($this->getServer()->getPluginManager()->getPlugin($eco) === null) {
            $this->getLogger()->alert("DISBALE PLUGIN ENCHANTTABLE BECAUSE NOT FOUND PLUGIN $eco");
            $this->getServer()->getPluginManager()->disablePlugin($this);
            return;
        }

        $this->getServer()->getPluginManager()->registerEvents(new EnchantmentEvent(), $this);
    }

    public function getMoney($player): int
    {
        $eco = $this->getConfig()->get("economy");
        if ($eco === "MoneyAPI") {
            return $this->getServer()->getPluginManager()->getPlugin($eco)->getMoneyPlayer($player);
        } elseif ($eco === "EconomyAPI") {
            return $this->getServer()->getPluginManager()->getPlugin($eco)->myMoney($player);
        } else return 0;
    }

    public function removeMoney($player, int $money): void
    {
        $eco = $this->getConfig()->get("economy");
        if ($eco === "MoneyAPI") {
            $this->getServer()->getPluginManager()->getPlugin($eco)->removeMoney($player, $money);
        } elseif ($eco === "EconomyAPI") {
            $this->getServer()->getPluginManager()->getPlugin($eco)->reduceMoney($player, $money);
        }
    }

    public static function getInstance(): Table
    {
        return self::$main;
    }
}