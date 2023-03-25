<?php

namespace THS\Commands\Sanction;

use pocketmine\command\CommandSender;
use pocketmine\command\PluginCommand;
use pocketmine\Player;
use THS\API\LanguageAPI;
use THS\Forms\BanForms;
use THS\Main;

class Ban extends PluginCommand{
    public function __construct(Main $main)
    {
        parent::__construct("ban", $main);
        $this->setPermission("ban.use");
    }

    public function execute(CommandSender $player, string $commandLabel, array $args)
    {
        if (!$player->hasPermission("unban.use")){
            LanguageAPI::sendMessage($player, "Vous n'avez pas la permission !", "You don't have permission!");
            return;
        }

        if (!($player instanceof Player)){
            LanguageAPI::sendMessage($player, "La commande doit être executer en jeu !", "");
            return;
        }

        BanForms::ban($player);
    }
}