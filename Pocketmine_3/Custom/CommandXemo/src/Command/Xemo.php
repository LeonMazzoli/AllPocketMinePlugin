<?php

namespace Command;

use Command\Commands\Craft;
use Command\Commands\Minage;
use Command\Commands\Spawn;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\event\Listener;
use pocketmine\Player;
use pocketmine\plugin\PluginBase;

class Xemo extends PluginBase implements Listener
{
    public static $xemo;
    public function onEnable()
    {
        $this->getLogger()->info("CommandXemo on by Digueloulou12");
        @mkdir($this->getDataFolder());
        if (!file_exists($this->getDataFolder() . "config.yml")) {
            $this->saveResource("config.yml");
        }

        $this->getServer()->getCommandMap()->register("spawn", new Spawn($this));
        $this->getServer()->getCommandMap()->register("minage", new Minage($this));
        $this->getServer()->getCommandMap()->register("craft", new Craft($this));

        self::$xemo = $this;

        @mkdir($this->getDataFolder());
        $this->saveDefaultConfig();
    }

    public function onDisable()
    {
        $this->getLogger()->info("CommandXemo off by Digueloulou12");
    }

    public function onCommand(CommandSender $player, Command $command, string $label, array $args): bool
    {
        switch ($command->getName()) {
            case "setminage":
                if ($player instanceof Player) {
                    $this->getConfig()->set('minage', [$player->getX(), $player->getY(), $player->getZ(), $player->getLevel()->getName()]);
                    $this->getConfig()->save();
                    $player->sendMessage($this->getConfig()->get("minage.good"));
                } else {
                    $player->sendMessage($this->getConfig()->get("use.ig"));
                }
                break;
            case "setcraft":
                if ($player instanceof Player) {
                    $this->getConfig()->set('craft', [$player->getX(), $player->getY(), $player->getZ(), $player->getLevel()->getName()]);
                    $this->getConfig()->save();
                    $player->sendMessage($this->getConfig()->get("craft.good"));
                } else {
                    $player->sendMessage($this->getConfig()->get("use.ig"));
                }
                break;
        }
        return true;
    }

    public static function getInstance(): Xemo {
        return self::$xemo;
    }

    public function removeTask($id)
    {
        $this->getScheduler()->cancelTask($id);
    }
}