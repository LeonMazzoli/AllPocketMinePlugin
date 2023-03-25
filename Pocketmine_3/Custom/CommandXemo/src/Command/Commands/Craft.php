<?php

namespace Command\Commands;

use Command\Task\CraftTask;
use Command\Xemo;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\Player;
use pocketmine\utils\Config;

class Craft extends Command{
    public function __construct(Xemo $main)
    {
        // $config = new Config(Xemo::getInstance()->getDataFolder() . "config.yml", Config::YAML);
        parent::__construct("craft", "Craft");
    }

    public function execute(CommandSender $player, string $commandLabel, array $args)
    {
        $config = new Config(Xemo::getInstance()->getDataFolder() . "config.yml", Config::YAML);
        if ($player instanceof Player){
            Xemo::getInstance()->getScheduler()->scheduleRepeatingTask(new CraftTask($player), 20);
        }else{
            $player->sendMessage($config->get("use.ig"));
        }
    }
}