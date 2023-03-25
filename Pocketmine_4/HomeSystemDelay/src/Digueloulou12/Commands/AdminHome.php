<?php

namespace Digueloulou12\Commands;

use pocketmine\command\CommandSender;
use Digueloulou12\HomeSystemDelay;
use pocketmine\command\Command;
use Digueloulou12\API\HomeAPI;
use pocketmine\player\Player;

class AdminHome extends Command
{
    public function __construct()
    {
        $command = explode(":", HomeSystemDelay::getConfigValue("adminhome_cmd"));
        parent::__construct($command[0]);
        if (isset($command[1])) $this->setDescription($command[1]);
        $this->setAliases(HomeSystemDelay::getConfigValue("adminhome_aliases"));
        $this->setPermission("admin.home");
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args)
    {
        if ($sender instanceof Player) {
            if ($sender->hasPermission("admin.home")) {
                if (isset($args[0])) {
                    if (HomeAPI::existPlayer($args[0])) {
                        if ((isset($args[1])) and (HomeAPI::existHome($args[0], $args[1]))) {
                            $sender->teleport(HomeAPI::getHome($args[0], $args[1]));
                            $sender->sendMessage(HomeSystemDelay::getConfigReplace("adminhome_msg_teleportation"));
                        } else {
                            $homes = implode(", ", HomeAPI::getAllHomes($args[0]));
                            $sender->sendMessage(HomeSystemDelay::getConfigReplace("adminhome_msg_list", ["{home}", "{player}"], [$homes, $args[0]]));
                        }
                    } else $sender->sendMessage(HomeSystemDelay::getConfigReplace("adminhome_no_exist_player"));
                } else $sender->sendMessage(HomeSystemDelay::getConfigReplace("adminhome_msg_no_player"));
            } else $sender->sendMessage(HomeSystemDelay::getConfigReplace("no_perm"));
        }
    }
}