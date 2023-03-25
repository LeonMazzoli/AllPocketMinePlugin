<?php

namespace Assassin\Commands;

use Assassin\Main;
use pocketmine\command\CommandSender;
use pocketmine\command\PluginCommand;
use pocketmine\Player;
use pocketmine\utils\Config;

class Spy extends PluginCommand{
    private $main;
    public static $spy = [];
    public function __construct(Main $main)
    {
        parent::__construct("spy", $main);
        $this->setDescription("Passe en mode espion");
        $this->setPermission("spy.use");
        $this->main = $main;
    }

    public function execute(CommandSender $player, string $commandLabel, array $args)
    {
        $config = new Config(Main::getInstance()->getDataFolder() . "config.yml", Config::YAML);
        if ($player instanceof Player){
            if ($player->hasPermission("spy.use")){
                if (empty(self::$spy[$player->getName()])){
                    self::$spy[$player->getName()] = $player;
                    $player->sendMessage(Main::$prefix . $config->get("spy.on"));
                }else{
                    unset(self::$spy[$player->getName()]);
                    $player->sendMessage(Main::$prefix . $config->get("spy.off"));
                }
            }else{
                $player->sendMessage(Main::$prefix . $config->get("noperms"));
            }
        }else{
            $player->sendMessage(Main::$prefix . $config->get("consolem"));
        }
    }
}