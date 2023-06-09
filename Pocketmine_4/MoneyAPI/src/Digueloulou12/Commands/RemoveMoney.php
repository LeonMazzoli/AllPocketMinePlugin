<?php

namespace Digueloulou12\Commands;

use Digueloulou12\Money;
use pocketmine\command\CommandSender;
use pocketmine\command\Command;
use pocketmine\player\Player;
use pocketmine\Server;

class RemoveMoney extends Command
{
    public function __construct()
    {
        $command = explode(":", Money::getConfigReplace("removemoney"));
        parent::__construct($command[0]);
        if (isset($command[1])) $this->setDescription($command[1]);
        $this->setAliases(Money::getConfigValue("removemoney_aliases"));
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args)
    {
        if (($sender instanceof Player)) {
            $command = explode(":", Money::getConfigReplace("removemoney"));
            if ((isset($command[2])) and (Money::hasPermissionPlayer($sender, $command[2]))) return;
            if (isset($args[0])) {
                $player = Server::getInstance()->getPlayerByPrefix($args[0]);
                if ($player instanceof Player) $name = $player->getName(); else $name = $args[0];
                if (Money::existPlayer($name)) {
                    if (isset($args[1])) {
                        if (is_numeric($args[1])) {
                            Money::removeMoney($name, $args[1]);
                            $sender->sendMessage(Money::getConfigReplace("removemoney_msg", [strtolower("{money}"), strtolower("{player}")], [$args[1], $name]));
                        } else $sender->sendMessage(Money::getConfigReplace("is_numeric"));
                    } else $sender->sendMessage(Money::getConfigReplace("is_numeric"));
                } else $sender->sendMessage(Money::getConfigReplace("no_exist_player"));
            } else $sender->sendMessage(Money::getConfigReplace("no_args_player"));
        }
    }
}