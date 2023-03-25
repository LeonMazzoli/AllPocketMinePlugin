<?php

namespace Digueloulou12\Commands;

use Digueloulou12\TopMain;
use pocketmine\command\CommandSender;
use pocketmine\command\PluginCommand;
use pocketmine\utils\Config;

class TopDeath extends PluginCommand
{
    public function __construct()
    {
        $config = new Config(TopMain::getInstance()->getDataFolder()."config.yml",Config::YAML);
        $command = explode(":", $config->get("topdeath"));
        parent::__construct($command[0], TopMain::getInstance());
        if ((isset($command[1])) and ($command[1] !== " ")) $this->setDescription($command[1]);
        if (isset($command[2])) $this->setPermission($command[2]);
        $this->setAliases($config->get("topdeath_aliases"));
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args)
    {
        $config = new Config(TopMain::getInstance()->getDataFolder()."config.yml",Config::YAML);

        $command = explode(":", $config->get("topdeath"));
        if ((isset($command[2])) and (!$sender->hasPermission($command[2]))) return;

        $sender->sendMessage($config->get("topdeath_title"));

        $kill = new Config(TopMain::getInstance()->getDataFolder() . "death.json", Config::JSON);

        $configs = $kill->getAll();
        arsort($configs);
        $value = 1;
        foreach ($configs as $name => $count) {
            if ($value !== $config->get("top_death") + 1) {
                $sender->sendMessage(str_replace(["{number}", "{name}", "{count}"], [$value, $name, $count], $config->get("topdeath_msg")));
                $value++;
            } else break;
        }
    }
}