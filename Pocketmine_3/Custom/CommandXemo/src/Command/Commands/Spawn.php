<?php

namespace Command\Commands;

use Command\Task\SpawnTask;
use Command\Xemo;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\Player;
use pocketmine\plugin\Plugin;
use pocketmine\utils\Config;

class Spawn extends Command{
    private $main;
    public function __construct(Xemo $main)
    {
        // $config = new Config(Xemo::getInstance()->getDataFolder() . "config.yml", Config::YAML);
        parent::__construct("spawn", "Spawn");
    }

    public function execute(CommandSender $player, string $commandLabel, array $args)
    {
        $config = new Config(Xemo::getInstance()->getDataFolder() . "config.yml", Config::YAML);
        if ($player instanceof Player){
            Xemo::getInstance()->getScheduler()->scheduleRepeatingTask(new SpawnTask($player), 20);
        }else{
            $player->sendMessage($config->get("use.ig"));
        }
    }

    public function getPlugin(): Plugin
    {
        return $this->main;
    }
}