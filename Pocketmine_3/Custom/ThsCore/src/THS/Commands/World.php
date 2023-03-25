<?php

namespace THS\Commands;

use pocketmine\command\CommandSender;
use pocketmine\command\PluginCommand;
use pocketmine\Player;
use THS\Main;

class World extends PluginCommand{
    public function __construct(Main $main)
    {
        parent::__construct("world", $main);
        $this->setPermission("world.use");
    }

    public function execute(CommandSender $player, string $commandLabel, array $args)
    {
        if (!($player instanceof Player)) return $player->sendMessage(Main::$prefix."La commande doit être executer en jeu !");
        return true;
    }
}