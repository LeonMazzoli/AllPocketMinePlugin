<?php

namespace Digueloulou12;

use pocketmine\event\player\PlayerJoinEvent;
use Digueloulou12\Command\AtmCommand;
use pocketmine\player\Player;
use pocketmine\plugin\PluginBase;
use Digueloulou12\Task\AtmTask;
use pocketmine\event\Listener;
use pocketmine\utils\Config;

class Atm extends PluginBase implements Listener
{
    private static Atm $atm;
    private Config $data;

    public function onEnable(): void
    {
        self::$atm = $this;
        $this->saveDefaultConfig();

        $this->data = new Config($this->getDataFolder() . "AtmData.json", Config::JSON);

        $money = $this->getConfig()->get("economy");
        if ($this->getServer()->getPluginManager()->getPlugin($money) === null) {
            $this->getServer()->getPluginManager()->disablePlugin($this);
            $this->getLogger()->alert("DISABLING PLUGIN ATM BECAUSE NOT FOUND PLUGIN $money !");
            return;
        }

        $this->getServer()->getPluginManager()->registerEvents($this, $this);
        $this->getServer()->getCommandMap()->register("", new AtmCommand());
    }

    public function onJoin(PlayerJoinEvent $event)
    {
        $money = $this->getConfig()->get("money");
        $this->getScheduler()->scheduleRepeatingTask(new AtmTask($event->getPlayer(), $money[0]), 20 * 60 * $money[1]);
    }

    public function onDisable(): void
    {
        $this->getAtmData()->save();
    }

    public function addMoney(Player $player, int $money_): void
    {
        $money = $this->getConfig()->get("economy");
        $plugin = $this->getServer()->getPluginManager()->getPlugin($money);
        if ($money === "BedrockEconomy") {
            $plugin->getAPI()->addToPlayerBalance($player->getName(), $money_);
        } elseif (($money === "MoneyAPI") or ($money === "EconomyAPI")) {
            $plugin->addMoney($player, $money_);
        }
    }

    public function getAtmData(): Config
    {
        return $this->data;
    }

    public static function getAtm(): Atm
    {
        return self::$atm;
    }
}