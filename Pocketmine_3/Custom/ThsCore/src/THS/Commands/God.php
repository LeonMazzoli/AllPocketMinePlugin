<?php

namespace THS\Commands;

use pocketmine\command\CommandSender;
use pocketmine\command\PluginCommand;
use pocketmine\Player;
use THS\API\LanguageAPI;
use THS\Main;
use THS\Events\GodEvent;

class God extends PluginCommand{
    public function __construct(Main $main)
    {
        parent::__construct("god", $main);
        $this->setPermission("god.use");
    }

    public function execute(CommandSender $player, string $commandLabel, array $args)
    {
        if (!($player instanceof Player)) return $player->sendMessage(Main::$ig);
        if (!$player->hasPermission("god.use")) return $player->sendMessage(Main::$noperm);

        if (isset(GodEvent::$god[$player->getName()])){
            unset(GodEvent::$god[$player->getName()]);
            LanguageAPI::sendMessage($player, "Vous venez de quitter le godmod !", "You have just left the godmod!");
        }else{
            GodEvent::$god[$player->getName()] = $player;
            LanguageAPI::sendMessage($player, "Vous venez de passer en godmod !", "You have just switched to godmod!");
        }
        return true;
    }
}