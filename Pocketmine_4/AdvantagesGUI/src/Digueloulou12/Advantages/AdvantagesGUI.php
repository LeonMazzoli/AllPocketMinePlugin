<?php

namespace Digueloulou12\Advantages;

use Digueloulou12\Advantages\Command\AdvantagesCommand;
use Digueloulou12\Advantages\Utils\MoneyUtils;
use Digueloulou12\Advantages\Utils\Utils;
use muqsit\invmenu\InvMenuHandler;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\Config;

class AdvantagesGUI extends PluginBase
{
    private static self $this;
    private Config $data;

    public function onEnable(): void
    {
        self::$this = $this;
        $this->saveDefaultConfig();

        $this->data = new Config($this->getDataFolder() . "AdvantagesData.json", Config::JSON);

        $plugin = $this->getServer()->getPluginManager()->getPlugin(Utils::getConfigValue("economy"));
        if (is_null($plugin)) {
            $this->getServer()->getPluginManager()->disablePlugin($this);
            $this->getLogger()->alert("THE PLUGIN CANNOT ACTIVATE BECAUSE IT CAN'T FIND " . Utils::getConfigValue("economy"));
            return;
        }
        MoneyUtils::setEconomy($plugin);

        if (!InvMenuHandler::isRegistered()) InvMenuHandler::register($this);

        $this->getServer()->getCommandMap()->register("AdvantagesCommand", new AdvantagesCommand(
            Utils::getConfigValue("command")[0] ?? "advantages",
            Utils::getConfigValue("command")[1] ?? "",
            Utils::getConfigValue("command_aliases") ?? []
        ));
    }

    public function getAdvantagesData(): Config
    {
        return $this->data;
    }

    public static function getInstance(): self
    {
        return self::$this;
    }

    public function onDisable(): void
    {
        $this->data->save();
    }
}