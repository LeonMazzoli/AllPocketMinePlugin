<?php

namespace THS\Commands\Sanction;

use pocketmine\command\CommandSender;
use pocketmine\command\PluginCommand;
use pocketmine\Player;
use THS\API\LanguageAPI;
use THS\Events\PlayerCommand;
use THS\Main;

class Spy extends PluginCommand{
    public function __construct(Main $main)
    {
        parent::__construct("spy", $main);
        $this->setPermission("spy.use");
    }

    public function execute(CommandSender $player, string $commandLabel, array $args)
    {
        if (!($player instanceof Player)){
            LanguageAPI::sendMessage($player, "La commande doit être executer en jeu !", "");
            return;
        }

        if (!$player->hasPermission("spy.use")){
            LanguageAPI::sendMessage($player, "Vous n'avez pas la permission !", "You don't have permission!");
            return;
        }

        if (!empty(PlayerCommand::$spy[$player->getName()])){
            unset(PlayerCommand::$spy[$player->getName()]);
            LanguageAPI::sendMessage($player, "Vous venez de quitter le mode espion !", "You have just left spy mode!");
        }else{
            PlayerCommand::$spy[$player->getName()] = $player;
            LanguageAPI::sendMessage($player, "Vous venez de rejoindre le mode espion !", "You have just joined spy mode!");
        }
    }
}