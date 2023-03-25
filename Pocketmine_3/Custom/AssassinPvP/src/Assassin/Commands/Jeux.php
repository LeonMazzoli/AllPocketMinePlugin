<?php

namespace Assassin\Commands;

use Assassin\Main;
use pocketmine\command\CommandSender;
use pocketmine\command\PluginCommand;
use pocketmine\Player;

class Jeux extends PluginCommand{
    private $main;
    public function __construct(Main $main)
    {
        parent::__construct("jeux", $main);
        $this->setDescription("Ouvre l'interface des jeux");
        $this->setPermission("jeux.use");
        $this->main = $main;
    }

    public function execute(CommandSender $player, string $commandLabel, array $args)
    {
        if ($player instanceof Player){

        }
    }
}