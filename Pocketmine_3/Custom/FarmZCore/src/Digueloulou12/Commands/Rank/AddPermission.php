<?php

namespace Digueloulou12\Commands\Rank;

use Digueloulou12\Main;
use Digueloulou12\API\RankAPI;
use pocketmine\command\CommandSender;
use pocketmine\command\PluginCommand;

class AddPermission extends PluginCommand
{
    public function __construct()
    {
        $command = explode(":", Main::getConfigAPI()->getConfigValue("addpermission"));
        parent::__construct($command[0], Main::getInstance());
        if (isset($command[1])) $this->setDescription($command[1]);
        if (isset($command[2])) $this->setPermission($command[2]);
        $this->setAliases(Main::getConfigAPI()->getConfigValue("addpermission_aliases"));
    }

    public function execute(CommandSender $player, string $commandLabel, array $args)
    {
        $command = explode(":", Main::getConfigAPI()->getConfigValue("addpermission"));
        if (isset($command[2])) {
            if (!$player->hasPermission($command[2])) {
                $player->sendMessage(Main::getConfigAPI()->getConfigValue("noperm"));
                return;
            }
        }

        if (!isset($args[0])) {
            $player->sendMessage(Main::getConfigAPI()->getConfigValue("addpermission_no_args"));
            return;
        }

        if (!RankAPI::existRank($args[0])) {
            $player->sendMessage(Main::getConfigAPI()->getConfigValue("addpermission_no_exist_rank"));
            return;
        }

        if (!isset($args[1])) {
            $player->sendMessage(Main::getConfigAPI()->getConfigValue("addpermission_no_args_perm"));
            return;
        }

        RankAPI::addPermission($args[0], $args[1]);
        $player->sendMessage(Main::getConfigAPI()->getConfigValue("addpermission_good", ["{rank}", "{perm}"], [$args[0], $args[1]]));
    }
}