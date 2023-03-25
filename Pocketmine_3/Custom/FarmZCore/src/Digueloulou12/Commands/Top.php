<?php

namespace Digueloulou12\Commands;

use Digueloulou12\Main;
use pocketmine\command\CommandSender;
use pocketmine\command\PluginCommand;
use pocketmine\Player;

class Top extends PluginCommand
{
    public function __construct()
    {
        $command = explode(":", Main::getConfigAPI()->getConfigValue("top"));
        parent::__construct($command[0], Main::getInstance());
        if (isset($command[1])) $this->setDescription($command[1]);
        if (isset($command[2])) $this->setPermission($command[2]);
        $this->setAliases(Main::getConfigAPI()->getConfigValue("top_aliases"));
    }

    public function execute(CommandSender $player, string $commandLabel, array $args)
    {
        $command = explode(":", Main::getConfigAPI()->getConfigValue("top"));
        if (isset($command[2])) {
            if (!$player->hasPermission($command[2])) {
                $player->sendMessage(Main::getConfigAPI()->getConfigValue("noperm"));
                return;
            }
        }

        if (!($player instanceof Player)) {
            $player->sendMessage(Main::getConfigAPI()->getConfigValue("noplayer"));
            return;
        }

        if (isset($args[0])) {
            if ($args[0] === "kill") {
                self::sendTop($player);
            } elseif ($args[0] === "death") {
                self::sendTop($player, "Death");
            } else $player->sendMessage(Main::getConfigAPI()->getConfigValue("top_msg_no_args"));
        } else $player->sendMessage(Main::getConfigAPI()->getConfigValue("top_msg_no_args"));
    }

    public static function sendTop(Player $player, string $top = "Kill")
    {
        $i = 1;
        $value = [];
        foreach (Main::$players->getAll() as $players => $key) {
            $value[$players] = $key[$top];
        }

        $player->sendMessage(Main::getConfigAPI()->getConfigValue("top_title"));

        arsort($value);
        foreach ($value as $name => $valuee) {
            if ($i === Main::$config->get("top_limite") + 1) {
                break;
            } else {
                $player->sendMessage(Main::getConfigAPI()->getConfigValue("top_msg", [strtolower("{num}"), strtolower("{player}"), strtolower("{value}"), strtolower("{name}")], [$i, $name, $valuee, $top]));
                $i++;
            }
        }
    }
}