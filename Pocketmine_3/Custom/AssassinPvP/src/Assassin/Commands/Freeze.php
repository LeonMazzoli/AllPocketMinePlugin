<?php

namespace Assassin\Commands;

use Assassin\Main;
use pocketmine\command\CommandSender;
use pocketmine\command\PluginCommand;
use pocketmine\Player;
use pocketmine\Server;

class Freeze extends PluginCommand{
    private $main;
    public function __construct(Main $main)
    {
        parent::__construct("freeze", $main);
        $this->setDescription("Freeze un joueur");
        $this->setPermission("freeze.use");
        $this->main = $main;
    }

    public function execute(CommandSender $player, string $commandLabel, array $args)
    {
        if (!($player instanceof Player)) return $player->sendMessage(Main::$prefix . "La commande doit être executer en jeu !");
        if (!$player->hasPermission("freeze.use")) return $player->sendMessage(Main::$prefix . "Vous n'avez pas la permission d'utiliser cette commande !");
        if (!isset($args[0])) return $player->sendMessage(Main::$prefix . "Vous devez indiquer un joueur !");
        if (Server::getInstance()->getPlayer($args[0]) === null) return $player->sendMessage(Main::$prefix . "Le joueur sélectionné na pas été trouvé !");


        $sender = Server::getInstance()->getPlayer($args[0]);
        if ($sender->isImmobile()) {
            $sender->setImmobile(false);
            $sender->sendMessage(Main::$prefix . "Vous venez d'être unfreeze par§a " . $player->getName());
            $player->sendMessage(Main::$prefix . "Vous venez d'unfreeze§a " . $sender->getName());
        } else {
            $sender->setImmobile(true);
            $sender->sendMessage(Main::$prefix . "Vous venez d'être freeze par§a " . $player->getName());
            $player->sendMessage(Main::$prefix . "Vous venez de freeze§a " . $sender->getName());
        }
        return true;
    }
}