<?php

namespace Digueloulou12\Commands;

use Digueloulou12\API\WarpAPI;
use Digueloulou12\Forms\WarpForms;
use Digueloulou12\Main;
use pocketmine\command\CommandSender;
use pocketmine\command\PluginCommand;
use pocketmine\Player;

class Warp extends PluginCommand
{
    public function __construct()
    {
        $command = explode(":", Main::getConfigAPI()->getConfigValue("warp"));
        parent::__construct($command[0], Main::getInstance());
        if (isset($command[1])) $this->setDescription($command[1]);
        if (isset($command[2])) $this->setPermission($command[2]);
        $this->setAliases(Main::getConfigAPI()->getConfigValue("warp_aliases"));
    }

    public function execute(CommandSender $player, string $commandLabel, array $args)
    {
        if (!$player instanceof Player) {
            $player->sendMessage(Main::getConfigAPI()->getConfigValue("noplayer"));
            return;
        }

        $command = explode(":", Main::getConfigAPI()->getConfigValue("warp"));
        if (isset($command[2])) {
            if (!$player->hasPermission($command[2])) {
                $player->sendMessage(Main::getConfigAPI()->getConfigValue("noperm"));
                return;
            }
        }

        if (isset($args[0])){
            if ($args[0] === "admin"){
                if ($player->hasPermission(Main::getConfigAPI()->getConfigValue("warp_permission_admin"))){
                    WarpForms::adminForm($player);
                } else WarpForms::formMain($player);
            } elseif ($args[0] === "list") {
                $warps = implode(", ", WarpAPI::listWarps());
                $player->sendMessage(Main::getConfigAPI()->getConfigValue("warp_list_warp", ["{warps}"], [$warps]));
            } else {
                if (WarpAPI::existWarp($args[0])){
                    WarpAPI::teleportToWarp($player, $args[0]);
                } else WarpForms::formMain($player);
            }
        } else WarpForms::formMain($player);
    }
}