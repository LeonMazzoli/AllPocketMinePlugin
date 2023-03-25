<?php

namespace CustomKb;

use pocketmine\command\CommandSender;
use pocketmine\command\PluginCommand;
use pocketmine\Player;
use pocketmine\Server;
use pocketmine\utils\Config;

class CustomKbCommand extends PluginCommand
{
    private $config;

    public function __construct(CustomMain $main)
    {
        $this->config = new Config($main->getDataFolder() . "config.yml", Config::YAML);
        $command = explode(":", $this->config->get("command"));
        parent::__construct($command[0], $main);
        if (isset($command[1])) $this->setDescription($command[1]);
        if (isset($command[2])) $this->setPermission($command[2]);
        $this->setAliases($this->config->get("command_aliases"));
    }

    public function execute(CommandSender $player, string $commandLabel, array $args)
    {
        $config = $this->config;

        $command = explode(":", $this->config->get("command"));
        if (isset($command[2])){
            if (!$player->hasPermission($command[2])){
                $player->sendMessage($config->get("no_perm"));
                return;
            }
        }

        if (!($player instanceof Player)) {
            if (!(isset($args[0])) or !(isset($args[1]))) {
                $player->sendMessage($config->get("no_args"));
                return;
            }

            if (!is_numeric($args[1])) {
                $player->sendMessage($config->get("is_numeric"));
                return;
            }

            if (Server::getInstance()->getLevelByName($args[0]) === null) {
                $player->sendMessage($config->get("no_world"));
                return;
            }

            $player->sendMessage(str_replace([strtolower('{world}'), strtolower('{kb}')], [$args[0], $args[1]], $config->get("customkb_message")));

            $config->setNested("kb.$args[0]", $args[1]);
            $config->save();
            return;
        }

        $world = $player->getLevel()->getName();

        if (!isset($args[0])) {
            $player->sendMessage($config->get("no_args"));
            return;
        }

        if (!is_numeric($args[0])) {
            $player->sendMessage($config->get("is_numeric"));
            return;
        }

        $player->sendMessage(str_replace([strtolower('{world}'), strtolower('{kb}')], [$world, $args[0]], $config->get("customkb_message")));

        $config->setNested("kb.$world", $args[0]);
        $config->save();
    }
}