<?php

namespace Command\Commands;

use Command\Evonia;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\utils\Config;

class Boutique extends Command{
    private $main;
    public function __construct(Evonia $main)
    {
        $config = new Config(Evonia::getInstance()->getDataFolder() . "config.yml", Config::YAML);
        parent::__construct("boutique", $config->get("desc.boutique"), "/boutique");
    }

    public function execute(CommandSender $player, string $commandLabel, array $args)
    {
        $config = new Config(Evonia::getInstance()->getDataFolder() . "config.yml", Config::YAML);
        $player->sendMessage($config->get("message.boutique"));
    }
}