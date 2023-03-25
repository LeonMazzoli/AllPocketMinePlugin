<?php

namespace Assassin\Commands;

use Assassin\Main;
use pocketmine\command\CommandSender;
use pocketmine\command\ConsoleCommandSender;
use pocketmine\command\PluginCommand;
use pocketmine\Player;
use pocketmine\Server;

class Giveallkey extends PluginCommand{
    public function __construct(Main $main)
    {
        parent::__construct("giveallkey", $main);
        $this->setPermission("giveallkey.use");
        $this->setDescription("Give une clef a tout les joueurs sur le serveur");
    }

    public function execute(CommandSender $player, string $commandLabel, array $args)
    {
        if (!($player instanceof Player)) return $player->sendMessage(Main::$prefix."La commande doit être executer en jeu !");
        if (!$player->hasPermission("giveallkey.use")) return $player->sendMessage(Main::$prefix."Vous n'avez pas la permission d'utiliser cette commande !");
        if (!isset($args[0])) return $player->sendMessage(Main::$prefix."Vous devez indiqué le genre de clef !");
        if (!isset($args[1])) return $player->sendMessage(Main::$prefix."Vous devez indiqué un chiffre de clef a give !");
        if (!is_numeric($args[1])) return $player->sendMessage(Main::$prefix."Vous devez indiqué une quantité en chiffre !");


        foreach (Server::getInstance()->getOnlinePlayers() as $sender){
            Server::getInstance()->getCommandMap()->dispatch(new ConsoleCommandSender(), "givekey {$sender->getName()} $args[0] $args[1]");
            $sender->sendMessage(Main::$prefix."Vous venez de recevoir§a $args[1] §fclef(s)§a $args[0]");
        }
        $player->sendMessage(Main::$prefix."Vous venez de give§a $args[1] §fclef(s)§a $args[0] §fau joueurs connecté");
        return true;
    }
}