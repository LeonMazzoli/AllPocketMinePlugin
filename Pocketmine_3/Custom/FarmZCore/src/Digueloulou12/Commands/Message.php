<?php

namespace Digueloulou12\Commands;

use Digueloulou12\Main;
use pocketmine\command\CommandSender;
use pocketmine\command\PluginCommand;
use pocketmine\Player;
use pocketmine\Server;

class Message extends PluginCommand
{
    public function __construct()
    {
        $command = explode(":", Main::getConfigAPI()->getConfigValue("msg"));
        parent::__construct($command[0], Main::getInstance());
        if (isset($command[1])) $this->setDescription($command[1]);
        if (isset($command[2])) $this->setPermission($command[2]);
        $this->setAliases(Main::getConfigAPI()->getConfigValue("msg_aliases"));
    }

    public function execute(CommandSender $player, string $commandLabel, array $args)
    {
        if (!$player instanceof Player) {
            $player->sendMessage(Main::getConfigAPI()->getConfigValue("noplayer"));
            return;
        }

        $command = explode(":", Main::getConfigAPI()->getConfigValue("msg"));
        if (isset($command[2])) {
            if (!$player->hasPermission($command[2])) {
                $player->sendMessage(Main::getConfigAPI()->getConfigValue("noperm"));
                return;
            }
        }

        if (!isset($args[0])) {
            $player->sendMessage(Main::getConfigAPI()->getConfigValue("no_args_player"));
            return;
        }

        $sender = Server::getInstance()->getPlayer($args[0]);
        if (!($sender instanceof Player)) {
            $player->sendMessage(Main::getConfigAPI()->getConfigValue("no_onlineplayer"));
            return;
        }

        $msg = implode(" ", array_splice($args, 1, 999));
        $sender->sendMessage(Main::getConfigAPI()->getConfigValue("msg_message", ["{player}", "{sender}", "{msg}"], [$player->getName(), $sender->getName(), $msg]));
        $player->sendMessage(Main::getConfigAPI()->getConfigValue("msg_message", ["{player}", "{sender}", "{msg}"], [$player->getName(), $sender->getName(), $msg]));
    }
}