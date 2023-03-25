<?php

namespace Digueloulou12\Command;

use pocketmine\command\CommandSender;
use Digueloulou12\Forms\ShopForms;
use pocketmine\command\Command;
use pocketmine\player\Player;
use Digueloulou12\Shop;

class ShopCommand extends Command
{
    public function __construct()
    {
        $command = explode(":", Shop::getConfigValue("command"));
        parent::__construct($command[0]);
        if (isset($command[1])) $this->setDescription($command[1]);
        $this->setAliases(Shop::getConfigValue("command_aliases"));
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args)
    {
        if ($sender instanceof Player) {
            $command = explode(":", Shop::getConfigValue("command"));
            if ((isset($command[2])) and !($sender->hasPermission($command[2]))) return;
            ShopForms::listCategory($sender);
        }
    }
}