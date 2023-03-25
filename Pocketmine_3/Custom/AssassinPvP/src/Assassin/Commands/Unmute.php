<?php

namespace Assassin\Commands;

use Assassin\Main;
use pocketmine\command\CommandSender;
use pocketmine\command\PluginCommand;
use pocketmine\Server;

class Unmute extends PluginCommand{
    private $main;
    public function __construct(Main $main)
    {
        parent::__construct("unmute", $main);
        $this->setDescription("Unmute un joueur");
        $this->setPermission("unmute.use");
        $this->main = $main;
    }

    public function execute(CommandSender $player, string $commandLabel, array $args)
    {
        if (!isset($args[0])) return $player->sendMessage(Main::$prefix."Vous devez indiquer un joueur a unmute !");
        if (!$player->hasPermission("unmute.use")) return $player->sendMessage(Main::$prefix."Vous n'avez pas la permission d'utiliser cette commande !");
        if (Server::getInstance()->getPlayer($args[0]) === null) return $player->sendMessage(Main::$prefix."Le joueur na pas été trouvé !");


        $sender = Server::getInstance()->getPlayer($args[0]);
        if (isset(Mute::$mute[$sender->getName()])){
            unset(Mute::$mute[$sender->getName()]);
            $player->sendMessage(Main::$prefix."Vous venez d'unmute§a " . $sender->getName());
            $sender->sendMessage(Main::$prefix."Vous venez d'être unmute par§a " . $player->getName());
        }else{
            $player->sendMessage(Main::$prefix."Le joueur n'est pas mute !");
        }
        return true;
    }
}