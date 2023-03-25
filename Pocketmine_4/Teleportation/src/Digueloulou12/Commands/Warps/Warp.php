<?php

namespace Digueloulou12\Commands\Warps;

use pocketmine\command\CommandSender;
use Digueloulou12\Teleportation;
use pocketmine\command\Command;
use Digueloulou12\API\WarpAPI;
use pocketmine\player\Player;

class Warp extends Command
{
    public function __construct()
    {
        $command = explode(":", Teleportation::getConfigValue("warp_cmd"));
        parent::__construct($command[0]);
        if (isset($command[1])) $this->setDescription($command[1]);
        $this->setAliases(Teleportation::getConfigValue("warp_aliases"));
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args)
    {
        if ($sender instanceof Player) {
            $command = explode(":", Teleportation::getConfigValue("warp_cmd"));
            if ((isset($command[2])) and (Teleportation::hasPermissionPlayer($sender, $command[2]))) return;
            if ((isset($args[0])) and (WarpAPI::existWarp($args[0]))) {
                $sender->teleport(WarpAPI::getWarp($args[0]));
                $sender->sendMessage(Teleportation::getConfigReplace("warp_msg_teleport"));
            } else {
                $warps = implode(", ", WarpAPI::getAllWarps());
                $sender->sendMessage(Teleportation::getConfigReplace("warp_msg_list", ["{warp}"], [$warps]));
            }
        }
    }
}