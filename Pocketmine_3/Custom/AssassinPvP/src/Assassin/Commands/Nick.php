<?php

namespace Assassin\Commands;

use Assassin\Main;
use pocketmine\command\CommandSender;
use pocketmine\command\PluginCommand;
use pocketmine\Player;
use pocketmine\Server;

class Nick extends PluginCommand{
    private $main;
    public function __construct(Main $main)
    {
        parent::__construct("nick", $main);
        $this->setDescription("Change le pseudo");
        $this->setPermission("nick.use");
    }

    public function execute(CommandSender $player, string $commandLabel, array $args)
    {
        if (!isset($args[1]) and !($player instanceof Player)) return $player->sendMessage(Main::$prefix."La commande doit être executer en jeu !");
        if (!$player->hasPermission("nick.use")) return $player->sendMessage(Main::$prefix."Vous n'avez pas la permission d'utiliser cette commande !");
        if (!isset($args[0])) return $player->sendMessage(Main::$prefix."Vous devez indiquer un nouveau pseudo !");
        if (isset($args[1]) and !$player->hasPermission("nick.user.use")) return $player->sendMessage(Main::$prefix."Vous n'avez pas la permission de nick un autre joueur !");
        if (isset($args[1]) and Server::getInstance()->getPlayer($args[0]) === null) return $player->sendMessage(Main::$prefix."Le joueur sélectionné na pas été trouvé !");


        if (isset($args[1])){
            $sender = Server::getInstance()->getPlayer($args[0]);
            $sender->setDisplayName($args[1]);
            $sender->setNameTag($args[1]);
            $player->sendMessage(Main::$prefix."Vous venez de changer le pseudo de§a {$sender->getName()} §fen§a $args[1] §f!");
            $sender->sendMessage(Main::$prefix."Votre pseudo vient d'être changer par§a {$player->getName()} §fen§a $args[1] §f!");
        }else{
            $player->setDisplayName($args[0]);
            $player->setNameTag($args[0]);
            $player->sendMessage(Main::$prefix."Vous venez de changer votre pseudo en§a $args[0] §f!");
        }
        return true;
    }
}