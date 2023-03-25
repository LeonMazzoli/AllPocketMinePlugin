<?php

namespace Digueloulou12;

use pocketmine\command\CommandSender;
use pocketmine\command\PluginCommand;
use pocketmine\Player;
use pocketmine\utils\Config;

class Farm2Command extends PluginCommand{
    public static $config;
    public function __construct(Farm2Main $main)
    {
        parent::__construct("f2w", $main);
        self::$config = new Config($main->getDataFolder()."config.yml",Config::YAML);
        if (self::$config->get("perm") !== ""){
            $this->setPermission(self::$config->get("perm"));
        }
        $this->setDescription(self::$config->get("desc"));
    }

    public function execute(CommandSender $player, string $commandLabel, array $args)
    {
        if (!($player instanceof Player)){
            $player->sendMessage(self::$config->get("console"));
            return;
        }

        Farm2Form::form($player);
    }
}