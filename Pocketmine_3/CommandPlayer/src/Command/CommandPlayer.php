<?php

namespace Command;

use pocketmine\command\CommandSender;
use pocketmine\command\PluginCommand;
use pocketmine\Player;
use pocketmine\Server;
use pocketmine\utils\Config;

class CommandPlayer extends PluginCommand{
    private $main;
    public function __construct(PlayerMain $main)
    {
        parent::__construct("command", $main);
        $config = new Config(PlayerMain::getInstance()->getDataFolder() . "config.yml", Config::YAML);
        $this->setDescription($config->get("desc"));
    }

    public function execute(CommandSender $player, string $commandLabel, array $args)
    {
        $config = new Config(PlayerMain::getInstance()->getDataFolder() . "config.yml", Config::YAML);
        if ($player instanceof Player){
            if ($player->hasPermission($config->get("perm"))){
                if (isset($args[0])){
                    if (Server::getInstance()->getPlayer($args[0]) != null){
                        if (isset($args[1])){
                            $sender = Server::getInstance()->getPlayer($args[0]);
                            Server::getInstance()->getCommandMap()->dispatch($sender, $args[1]);
                            $message = $config->get("command.good");
                            $custommessage = str_replace(["{command}", "{player}"], [$args[1], $sender->getName()], $message);
                            $player->sendMessage($custommessage);
                        }else{
                            $player->sendMessage($config->get("no.command"));
                        }
                    }else{
                        $player->sendMessage($config->get("offline"));
                    }
                }else{
                    $player->sendMessage($config->get("no.player"));
                }
            }else{
                $player->sendMessage($config->get("no.perm"));
            }
        }else{
            $player->sendMessage($config->get("console"));
        }
    }
}