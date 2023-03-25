<?php

namespace Assassin\Commands;

use Assassin\Main;
use pocketmine\command\CommandSender;
use pocketmine\command\PluginCommand;
use pocketmine\level\Position;
use pocketmine\Player;
use pocketmine\Server;
use pocketmine\utils\Config;

class Goville extends PluginCommand{
    public function __construct(Main $main)
    {
        parent::__construct("goville", $main);
        $this->setDescription("Téléporte a la ville");
        $this->setPermission("ville.use");
    }

    public function execute(CommandSender $player, string $commandLabel, array $args)
    {
        $config = new Config(Main::getInstance()->getDataFolder()."config.yml",Config::YAML);
        if (!($player instanceof Player)) return $player->sendMessage(Main::$prefix."La commande doit être executer en jeu !");
        if ($config->get("ville") === null) return $player->sendMessage(Main::$prefix."Le point de spawn ne la ville na pas encore été définie !");

        $x = $config->get("ville")[0];
        $y = $config->get("ville")[1];
        $z = $config->get("ville")[2];
        $monde = $config->get("ville")[3];
        $player->teleport(new Position($x, $y, $z, Server::getInstance()->getLevelByName($monde)));
        $player->sendMessage(Main::$prefix."Vous venez de vous téléporté a la ville !");
        return true;
    }
}