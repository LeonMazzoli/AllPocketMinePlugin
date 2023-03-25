<?php

namespace THS\Commands\Sanction;

use pocketmine\command\CommandSender;
use pocketmine\command\PluginCommand;
use THS\API\LanguageAPI;
use THS\Main;

class Vanish extends PluginCommand{
    public function __construct(Main $main)
    {
        parent::__construct("vanish", $main);
        $this->setPermission("vanish.use");
    }

    public function execute(CommandSender $player, string $commandLabel, array $args)
    {
        if (!$player->hasPermission("vanish.use")){
            LanguageAPI::sendMessage($player, "Vous n'avez pas la permission !", "You don't have permission!");
            return;
        }
    }
}