<?php

namespace Digueloulou12\Commands;

use pocketmine\command\CommandSender;
use pocketmine\command\Command;
use Digueloulou12\API\WarpAPI;
use pocketmine\Player;

class DelWarp extends Command
{
    public function __construct()
    {
        $command = explode(":", \Digueloulou12\Warp::getConfigValue("delwarp_cmd"));
        parent::__construct($command[0]);
        if (isset($command[1])) $this->setDescription($command[1]);
        $this->setAliases(\Digueloulou12\Warp::getConfigValue("delwarp_aliases"));
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args)
    {
        if ($sender instanceof Player) {
            $command = explode(":", \Digueloulou12\Warp::getConfigValue("delwarp_cmd"));
            if ((isset($command[2])) and (\Digueloulou12\Warp::hasPermissionPlayer($sender, $command[2]))) return;
            if (isset($args[0])) {
                if (WarpAPI::existWarp($args[0])) {
                    WarpAPI::delWarp($args[0]);
                    $sender->sendMessage(\Digueloulou12\Warp::getConfigReplace("delwarp_good"));
                } else $sender->sendMessage(\Digueloulou12\Warp::getConfigReplace("delwarp_msg_no_exist"));
            } else $sender->sendMessage(\Digueloulou12\Warp::getConfigReplace("delwarp_on_warp"));
        }
    }
}