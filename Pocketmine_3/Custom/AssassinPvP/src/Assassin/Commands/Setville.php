<?php

namespace Assassin\Commands;

use Assassin\Main;
use pocketmine\command\CommandSender;
use pocketmine\command\PluginCommand;
use pocketmine\Player;
use pocketmine\utils\Config;

class Setville extends PluginCommand{
    public function __construct(Main $main)
    {
        parent::__construct("setville", $main);
        $this->setDescription("Définie le point de téléportation de la ville");
        $this->setPermission("setville.use");
    }

    public function execute(CommandSender $player, string $commandLabel, array $args)
    {
        if (!($player instanceof Player)) return $player->sendMessage(Main::$prefix."La commande doit être executer en jeu !");
        if (!$player->hasPermission("setville.use")) return $player->sendMessage(Main::$prefix."Vous n'avez pas la permission d'utiliser cette commande !");


        $config = new Config(Main::getInstance()->getDataFolder()."config.yml",Config::YAML);
        $config->set('ville', [$player->getX(), $player->getY(), $player->getZ(), $player->getLevel()->getName()]);
        $config->save();
        $player->sendMessage(Main::$prefix."Vous venez de définir le point de téléportation de la ville !");
        return true;
    }
}