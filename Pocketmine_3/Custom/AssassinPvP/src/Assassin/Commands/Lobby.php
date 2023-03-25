<?php

namespace Assassin\Commands;

use Assassin\Main;
use pocketmine\command\CommandSender;
use pocketmine\command\PluginCommand;
use pocketmine\Player;
use pocketmine\Server;
use pocketmine\utils\Config;

class Lobby extends PluginCommand{
    private $main;
    public function __construct(Main $main)
    {
        parent::__construct("lobby", $main);
        $this->setDescription("Connecte sur le serveur lobby");
        $this->main = $main;
    }

    public function execute(CommandSender $player, string $commandLabel, array $args)
    {
        $config = new Config(Main::getInstance()->getDataFolder() . "config.yml", Config::YAML);
        if (isset($args[0])){
            if ($player->hasPermission("lobby.use")){
                if (Server::getInstance()->getPlayer($args[0]) != null){
                    $sender = Server::getInstance()->getPlayer($args[0]);
                    $sender->transfer($config->get("ip"), $config->get("port"));
                    $player->sendMessage(Main::$prefix . str_replace("{player}", $sender->getName(), $config->get("good")));
                }else{
                    $player->sendMessage(Main::$prefix . $config->get("noplayer"));
                }
            }else{
                $player->sendMessage(Main::$prefix . $config->get("noperm"));
            }
        }else{
            if ($player instanceof Player){
                $player->transfer($config->get("ip"), $config->get("port"));
            }else{
                $player->sendMessage(Main::$prefix . $config->get("console"));
            }
        }
    }
}