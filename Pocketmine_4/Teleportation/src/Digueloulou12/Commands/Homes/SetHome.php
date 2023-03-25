<?php

namespace Digueloulou12\Commands\Homes;

use pocketmine\command\CommandSender;
use Digueloulou12\Teleportation;
use pocketmine\command\Command;
use Digueloulou12\API\HomeAPI;
use pocketmine\player\Player;

class SetHome extends Command
{
    public function __construct()
    {
        $command = explode(":", Teleportation::getConfigValue("sethome_cmd"));
        parent::__construct($command[0]);
        if (isset($command[1])) $this->setDescription($command[1]);
        $this->setAliases(Teleportation::getConfigValue("sethome_aliases"));
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args)
    {
        if ($sender instanceof Player) {
            $command = explode(":", Teleportation::getConfigValue("sethome_cmd"));
            if ((isset($command[2])) and (Teleportation::hasPermissionPlayer($sender, $command[2]))) return;

            if (!in_array($sender->getWorld()->getDisplayName(), Teleportation::getConfigValue("disabled-worlds"))) {
                if (isset($args[0])) {
                    if (!HomeAPI::existHome($sender, $args[0])) {
                        HomeAPI::setHome($sender, $sender, $args[0]);
                        $sender->sendMessage(Teleportation::getConfigReplace("sethome_msg_good"));
                    } else $sender->sendMessage(Teleportation::getConfigReplace("sethome_msg_exist_home"));
                } else $sender->sendMessage(Teleportation::getConfigReplace("sethome_msg_no_home"));
            } else $sender->sendMessage(Teleportation::getConfigReplace("home_no_world"));
        }
    }
}