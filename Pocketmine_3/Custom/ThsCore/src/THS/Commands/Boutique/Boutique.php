<?php

namespace THS\Commands\Boutique;

use pocketmine\command\CommandSender;
use pocketmine\command\PluginCommand;
use pocketmine\Player;
use THS\Forms\BoutiqueForms;
use THS\Main;

class Boutique extends PluginCommand{
    public function __construct(Main $main)
    {
        parent::__construct("boutique", $main);
    }

    public function execute(CommandSender $player, string $commandLabel, array $args)
    {
        if (!($player instanceof Player)){
            $player->sendMessage(Main::$prefix."La commande doit être executer en jeu !");
            return true;
        }

        BoutiqueForms::form($player);
        return true;
    }
}