<?php

namespace Digueloulou12;

use Digueloulou12\Command\ShopCommand;
use pocketmine\plugin\PluginBase;
use pocketmine\Server;

class Shop extends PluginBase
{
    private static Shop $main;

    public function onEnable(): void
    {
        self::$main = $this;
        $this->saveResource("config.yml");

        $economy = $this->getConfig()->get("money");
        if (($economy === "EconomyAPI") or ($economy === "MoneyAPI")) {
            if ($this->getServer()->getPluginManager()->getPlugin($economy) === null) {
                $this->getServer()->getPluginManager()->disablePlugin($this);
                $this->getLogger()->alert("DISABLE PLUGIN -> NO PLUGIN $economy");
                return;
            }
        } else {
            $this->getServer()->getPluginManager()->disablePlugin($this);
            $this->getLogger()->alert("DISABLE PLUGIN -> NO PLUGIN $economy");
            return;
        }

        $this->getServer()->getCommandMap()->register("", new ShopCommand());
    }

    public static function getInstance(): Shop
    {
        return self::$main;
    }

    public static function getConfigReplace(string $path, array $replace = [], array $replacer = []): string
    {
        $return = str_replace("{prefix}", self::$main->getConfig()->get("prefix"), self::$main->getConfig()->get($path));
        return str_replace($replace, $replacer, $return);
    }

    public static function getConfigValue(string $path): mixed
    {
        return self::$main->getConfig()->get($path);
    }

    public function getMoney($player): int|float
    {
        if (Shop::getConfigValue("money") === "MoneyAPI") {
            return Server::getInstance()->getPluginManager()->getPlugin("MoneyAPI")->getMoneyPlayer($player);
        } else return Server::getInstance()->getPluginManager()->getPlugin("EconomyAPI")->myMoney($player);
    }

    public function removeMoney($player, int $amount): void
    {
        if (Shop::getConfigValue("money") === "MoneyAPI") {
            Server::getInstance()->getPluginManager()->getPlugin("MoneyAPI")->removeMoney($player, $amount);
        } else Server::getInstance()->getPluginManager()->getPlugin("EconomyAPI")->reduceMoney($player, $amount);
    }
}