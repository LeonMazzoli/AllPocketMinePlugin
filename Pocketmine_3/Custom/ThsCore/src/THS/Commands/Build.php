<?php

namespace THS\Commands;

use pocketmine\command\CommandSender;
use pocketmine\command\PluginCommand;
use pocketmine\Player;
use pocketmine\Server;
use THS\API\LanguageAPI;
use THS\Main;

class Build extends PluginCommand{
    public function __construct(Main $main)
    {
        parent::__construct("build", $main);
        $this->setPermission("build.use");
    }

    public function execute(CommandSender $player, string $commandLabel, array $args)
    {
        if (!($player instanceof Player)){
            $player->sendMessage(Main::$ig);
            return;
        }

        if (!($player->hasPermission("build.use"))){
            LanguageAPI::sendMessage($player, "Vous n'avez pas la permission d'utiliser cette commande !", "You don't have a permission !");
            return;
        }

        $player->setGamemode(1);

        Server::getInstance()->getCommandMap()->dispatch($player, "mw tp spawn2");
    }
}