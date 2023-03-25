<?php

namespace THS\Commands;

use pocketmine\command\CommandSender;
use pocketmine\command\PluginCommand;
use pocketmine\Server;
use THS\API\LanguageAPI;
use THS\Main;

class Tps extends PluginCommand{
    public function __construct(Main $main)
    {
        parent::__construct("tps", $main);
    }

    public function execute(CommandSender $player, string $commandLabel, array $args)
    {
        $tps = Server::getInstance()->getTicksPerSecond();
        LanguageAPI::sendMessage($player, "Le serveur est à§a $tps §fde tps !", "The server is at§a $tps §fof tps!");
        return true;
    }
}