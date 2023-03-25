<?php

namespace Digueloulou12\Commands;

use pocketmine\command\CommandSender;
use pocketmine\command\Command;
use pocketmine\player\Player;
use Digueloulou12\Money;

class MyMoney extends Command
{
    public function __construct()
    {
        $command = explode(":", Money::getConfigReplace("mymoney"));
        parent::__construct($command[0]);
        if (isset($command[1])) $this->setDescription($command[1]);
        $this->setAliases(Money::getConfigValue("mymoney_aliases"));
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args)
    {
        if (($sender instanceof Player)) {
            $command = explode(":", Money::getConfigReplace("mymoney"));
            if ((isset($command[2])) and (Money::hasPermissionPlayer($sender, $command[2]))) return;
            $sender->sendMessage(Money::getConfigReplace("mymoney_msg", [strtolower("{money}")], [Money::getMoneyPlayer($sender)]));
        }
    }
}