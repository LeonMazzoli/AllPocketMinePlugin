<?php

namespace THS\Commands;

use pocketmine\command\CommandSender;
use pocketmine\command\PluginCommand;
use pocketmine\Player;
use pocketmine\utils\Config;
use THS\Main;

class Setpos extends PluginCommand{
    public function __construct(Main $main)
    {
        parent::__construct("setpos", $main);
        $this->setPermission("setpos.use");
    }

    public function execute(CommandSender $player, string $commandLabel, array $args)
    {
        if (!$player->hasPermission("setpos.use")) return $player->sendMessage(Main::$noperm);
        if (!($player instanceof Player)) return $player->sendMessage(Main::$ig);


        $config = new Config(Main::getInstance()->getDataFolder()."config.yml");
        $name = $player->getLevel()->getName();
        $config->set($name, [$player->getX(), $player->getY(), $player->getZ(), $name]);
        $config->save();
        $player->sendMessage(Main::$prefix."Vous venez de définir le point de spawn du monde§a $name §f!");
        return true;
    }
}