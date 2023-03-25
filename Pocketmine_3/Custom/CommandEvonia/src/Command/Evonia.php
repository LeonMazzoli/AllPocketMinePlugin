<?php

namespace Command;

use Command\Commands\Boutique;
use Command\Commands\Discord;
use Command\Commands\Furnace;
use Command\Commands\Rtp;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\event\Listener;
use pocketmine\plugin\PluginBase;

class Evonia extends PluginBase implements Listener{
    private static $main;
    public function onEnable()
    {
        @mkdir($this->getDataFolder());
        if (!file_exists($this->getDataFolder() . "config.yml")){
            $this->saveResource("config.yml");
        }

        $this->getLogger()->info("CommandEvonia on by Digueloulou12");
        self::$main = $this;
        $this->getServer()->getCommandMap()->register("discord", new Discord($this));
        $this->getServer()->getCommandMap()->register("boutique", new Boutique($this));
        $this->getServer()->getCommandMap()->register("furnace", new Furnace($this));
        $this->getServer()->getCommandMap()->register("rtp", new Rtp($this));
    }

    public function onDisable()
    {
        $this->getLogger()->info("CommandEvonia off by Digueloulou12");
    }

    public function onCommand(CommandSender $player, Command $command, string $label, array $args): bool
    {
        switch ($command->getName()){
            case "commandreload":
                $this->getConfig()->reload();
                $player->sendMessage("Les configs ont bien été reload !");
                break;
        }
        return true;
    }

    public static function getInstance(): Evonia {
        return self::$main;
    }
}