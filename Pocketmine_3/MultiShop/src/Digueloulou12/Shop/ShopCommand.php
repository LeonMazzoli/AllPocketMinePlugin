<?php

namespace Digueloulou12\Shop;

use pocketmine\command\CommandSender;
use pocketmine\command\PluginCommand;
use Digueloulou12\MultiMain;
use pocketmine\utils\Config;
use pocketmine\Player;

class ShopCommand extends PluginCommand
{
    public function __construct()
    {
        $config = new Config(MultiMain::getInstance()->getDataFolder() . "config.yml", Config::YAML);
        $command = explode(":", $config->get("command"));
        parent::__construct($command[0], MultiMain::getInstance());
        if (isset($command[1])) $this->setDescription($command[1]);
        if (isset($command[2])) $this->setPermission($command[2]);
        $this->setAliases($config->get("command_aliases"));
    }

    public function execute(CommandSender $player, string $commandLabel, array $args)
    {
        $config = new Config(MultiMain::getInstance()->getDataFolder() . "config.yml", Config::YAML);
        if (!($player instanceof Player)) {
            $player->sendMessage($config->get("noplayer"));
            return;
        }

        $command = explode(":", $config->get("command"));
        if (isset($command[2])) {
            if (!$player->hasPermission($command[2])) {
                $player->sendMessage($config->get("noperm"));
                return;
            }
        }

        if (isset($args[0])) {
            if ($config->getNested("shop.$args[0]") === null) {
                $player->sendMessage($config->get("noexistshop"));
                return;
            }

            if ($config->getNested("shop.$args[0].type") === "category") {
                ShopForms::listCategory($player, $args[0]);
            } else ShopForms::listItem($player, $args[0]);
        } else {
            if ($config->get("default_shop") === " ") {
                $player->sendMessage($config->get("noshopdefault"));
            } else {
                if ($config->getNested("shop.{$config->get("default_shop")}.type") === "category") {
                    ShopForms::listCategory($player, $config->get("default_shop"));
                } else ShopForms::listItem($player, $config->get("default_shop"));
            }
        }
    }
}