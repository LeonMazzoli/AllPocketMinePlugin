<?php

namespace Digueloulou12\Command;

use Digueloulou12\AnvilMain;
use Digueloulou12\Forms\AnvilForms;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;
use pocketmine\utils\Config;

class AnvilCommand extends Command
{
    public function __construct()
    {
        $config = new Config(AnvilMain::getInstance()->getDataFolder() . "config.yml", Config::YAML);
        $command = explode(":", $config->get("command"));
        parent::__construct($command[0]);
        if (isset($command[1])) $this->setDescription($command[1]);
        $this->setAliases($config->get("command_aliases"));
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args)
    {
        $config = new Config(AnvilMain::getInstance()->getDataFolder() . "config.yml", Config::YAML);
        if ($sender instanceof Player) {
            $command = explode(":", $config->get("command"));
            if (isset($command[2])) {
                if (!$sender->hasPermission($command[2])) return;
            }

            AnvilForms::mainForm($sender);
        }
    }
}