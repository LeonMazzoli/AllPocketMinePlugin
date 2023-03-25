<?php

namespace THS\Commands;

use pocketmine\command\CommandSender;
use pocketmine\command\PluginCommand;
use pocketmine\Server;
use pocketmine\utils\Config;
use THS\Main;

class Setkb extends PluginCommand{
    public function __construct(Main $main)
    {
        parent::__construct("setkb", $main);
        $this->setPermission("setkb.use");
    }

    public function execute(CommandSender $player, string $commandLabel, array $args)
    {
        if (!$player->hasPermission("setkb.use")) return $player->sendMessage(Main::$noperm);
        if (!isset($args[0])) return $player->sendMessage(Main::$prefix."Vous devez indiquer un monde !");
        if (!isset($args[1])) return $player->sendMessage(Main::$prefix."Vous devez indiquer un kb !");
        if (!is_numeric($args[1])) return $player->sendMessage(Main::$prefix."Vous devez indiquer un kb en chiffre !");
        if (Server::getInstance()->getLevelByName($args[0])->getSpawnLocation() === null) return $player->sendMessage($player->sendMessage(Main::$prefix."Le nom indiquer n'est pas indiquer !"));


        $config = new Config(Main::getInstance()->getDataFolder()."config.yml",Config::YAML);
        $config->setNested("kb.$args[0]", $args[1]);
        $config->save();
        return true;
    }
}