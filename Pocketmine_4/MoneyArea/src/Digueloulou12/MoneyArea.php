<?php

namespace Digueloulou12;

use Digueloulou12\Task\MoneyTask;
use pocketmine\plugin\PluginBase;
use pocketmine\player\Player;

class MoneyArea extends PluginBase
{
    private static MoneyArea $main;
    private string $money;

    public function onEnable(): void
    {
        self::$main = $this;
        $this->saveDefaultConfig();

        $economy = $this->getConfig()->get("economy");
        if ($this->getServer()->getPluginManager()->getPlugin($economy) === null) {
            $this->getServer()->getPluginManager()->disablePlugin($this);
            $this->getLogger()->alert("MoneyArea IS DISABLING BECAUSE NOT FOUND PLUGIN $economy");
            return;
        }

        $this->money = $economy;

        if ($this->getServer()->getWorldManager()->getWorldByName($this->getConfig()->get("world")) === null) {
            $this->getLogger()->alert("THE WORLD {$this->getConfig()->get("world")} NO EXIST !");
            $this->getServer()->getPluginManager()->disablePlugin($this);
        } else $this->getScheduler()->scheduleRepeatingTask(new MoneyTask(), 20 * $this->getConfig()->get("time"));
    }

    public function isInArea(Player $player): bool
    {
        $pos1 = $this->getConfig()->get("pos");
        $pos2 = $this->getConfig()->get("pos_");
        if (($player->getPosition()->x >= min($pos1[0], $pos2[0])) and ($player->getPosition()->x <= max($pos1[0], $pos2[0])) and
            ($player->getPosition()->y >= min($pos1[1], $pos2[1])) and ($player->getPosition()->y <= max($pos1[1], $pos2[1])) and
            ($player->getPosition()->z >= min($pos1[2], $pos2[2])) and ($player->getPosition()->z <= max($pos1[2], $pos2[2]))) {
            return true;
        }
        return false;
    }

    public function addMoney(Player $player, int $amount): void
    {
        $plugin = $this->getServer()->getPluginManager()->getPlugin($this->money);
        if ($this->money === "BedrockEconomy") {
            $plugin->getAPI()->addToPlayerBalance($player->getName(), $amount);
        } else $plugin->addMoney($player, $amount);
    }

    public static function getInstance(): MoneyArea
    {
        return self::$main;
    }
}