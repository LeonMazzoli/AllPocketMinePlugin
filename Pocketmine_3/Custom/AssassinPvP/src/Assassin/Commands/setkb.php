<?php

namespace Assassin\Commands;

use Assassin\Main;
use pocketmine\command\CommandSender;
use pocketmine\command\PluginCommand;
use pocketmine\utils\Config;

class setkb extends PluginCommand{
    private $main;
    public function __construct(Main $main)
    {
        parent::__construct("setkb", $main);
        $this->setDescription("Définie le kb");
        $this->setPermission("kb.use");
        $this->main = $main;
    }

    public function execute(CommandSender $player, string $commandLabel, array $args)
    {
        $config = new Config(Main::getInstance()->getDataFolder() . "config.yml", Config::YAML);
        if ($player->hasPermission("kb.use")){
            if (isset($args[0])){
                if (is_numeric($args[0])){
                    $config->set("kb", $args[0]);
                    $config->save();
                    $player->sendMessage(Main::$prefix .  "§fVous venez de définir le kb sur§a $args[0]");
                }else{
                    $player->sendMessage(Main::$prefix . "§fLe kb doit être en chiffre !");
                }
            }else{
                $player->sendMessage(Main::$prefix . "§fVous devez indiqué un kb !");
            }
        }else{
            $player->sendMessage(Main::$prefix . "§fVous n'avez pas la permission de définir les kb !");
        }
    }
}