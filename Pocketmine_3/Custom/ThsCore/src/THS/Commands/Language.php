<?php

namespace THS\Commands;

use pocketmine\command\CommandSender;
use pocketmine\command\PluginCommand;
use pocketmine\Server;
use pocketmine\utils\Config;
use THS\API\LanguageAPI;
use THS\Main;

class Language extends PluginCommand{
    public function __construct(Main $main)
    {
        parent::__construct("language", $main);
    }

    public function execute(CommandSender $player, string $commandLabel, array $args)
    {
        $language = new Config(Main::getInstance()->getDataFolder() . "language.json", Config::JSON);
        $name = strtolower($player->getName());
        if (!isset($args[0])){
            LanguageAPI::sendMessage($player, "Vous devez indiquer un joueur !", "You must indicate a player!");
            return;
        }

        if (Server::getInstance()->getPlayer($args[0]) === null){
            $sender = strtolower($args[0]);
        }else $sender = strtolower(Server::getInstance()->getPlayer($args[0])->getName());

        if ($language->get($sender) === "fr") $l = "Français"; else $l = "English";
        LanguageAPI::sendMessage($player, "Le joueur§a $sender §fà définie la langue de son jeu sur §a$l §f!", "The player§a $sender §fset the language of his game to§a $l §f!");
    }
}