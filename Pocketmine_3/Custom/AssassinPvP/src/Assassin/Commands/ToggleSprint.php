<?php

namespace Assassin\Commands;

use Assassin\Main;
use Assassin\Tasks\ToggleSprintTask;
use pocketmine\command\CommandSender;
use pocketmine\command\PluginCommand;
use pocketmine\Player;
use pocketmine\utils\Config;

class ToggleSprint extends PluginCommand{
    private $main;
    public function __construct(Main $main)
    {
        parent::__construct("togglesprint", $main);
        $this->setDescription("Active ou désactive le togglesprint");
        $this->main = $main;
    }

    public function execute(CommandSender $player, string $commandLabel, array $args)
    {
        $config = new Config(Main::getInstance()->getDataFolder() . "config.yml", Config::YAML);
        if ($player instanceof Player){
            if (isset($args[0])){
                if ($args[0] == "on"){
                    $player->sendMessage(Main::$prefix . $config->get("toggle.on"));
                    Main::getInstance()->getScheduler()->scheduleRepeatingTask(new ToggleSprintTask($player), 1);
                }elseif ($args[0] == "off"){
                    $player->sendMessage(Main::$prefix . $config->get("toggle.off"));
                    Main::getInstance()->getScheduler()->cancelAllTasks();
                }else{
                    $player->sendMessage(Main::$prefix . $config->get("toggle.args"));
                }
            }else{
                $player->sendMessage(Main::$prefix . $config->get("toggle.args"));
            }
        }
    }
}