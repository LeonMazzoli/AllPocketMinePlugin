<?php

namespace Command\Commands;

use Command\Evonia;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\utils\Config;

class Discord extends Command{
    private $main;
    public function __construct(Evonia $main)
    {
        $config = new Config(Evonia::getInstance()->getDataFolder() . "config.yml", Config::YAML);
        parent::__construct("discord", $config->get("desc.discord"), "/discord");
    }

    public function execute(CommandSender $player, string $commandLabel, array $args)
    {
        $config = new Config(Evonia::getInstance()->getDataFolder() . "config.yml", Config::YAML);
        $player->sendMessage($config->get("message.discord"));
    }
}