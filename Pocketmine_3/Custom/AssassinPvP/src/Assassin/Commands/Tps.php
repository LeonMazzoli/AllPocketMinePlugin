<?php

namespace Assassin\Commands;

use Assassin\Main;
use pocketmine\command\CommandSender;
use pocketmine\command\PluginCommand;
use pocketmine\Server;

class Tps extends PluginCommand{
    private $main;
    public function __construct(Main $main)
    {
        parent::__construct("tps", $main);
        $this->setDescription("Regarde le tps du serveur");
        $this->main = $main;
    }

    public function execute(CommandSender $player, string $commandLabel, array $args)
    {
        $player->sendMessage(Main::$prefix . "§fLe serveur est à§a " . Server::getInstance()->getTicksPerSecond());
    }
}