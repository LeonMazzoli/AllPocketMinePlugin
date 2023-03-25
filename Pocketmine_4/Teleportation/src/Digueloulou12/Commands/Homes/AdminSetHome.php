<?php

namespace Digueloulou12\Commands\Homes;

use pocketmine\command\CommandSender;
use Digueloulou12\Teleportation;
use pocketmine\command\Command;
use Digueloulou12\API\HomeAPI;
use pocketmine\player\Player;

class AdminSetHome extends Command
{
    public function __construct()
    {
        $command = explode(":", Teleportation::getConfigValue("adminsethome_cmd"));
        parent::__construct($command[0]);
        if (isset($command[1])) $this->setDescription($command[1]);
        $this->setAliases(Teleportation::getConfigValue("adminsethome_aliases"));
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args)
    {
        if ($sender instanceof Player) {
            $command = explode(":", Teleportation::getConfigValue("adminsethome_cmd"));
            if ((isset($command[2])) and (Teleportation::hasPermissionPlayer($sender, $command[2]))) return;
            if (isset($args[0])) {
                if (HomeAPI::existPlayer($args[0])) {
                    if (isset($args[1])) {
                        if (!HomeAPI::existHome($args[0], $args[1])) {
                            HomeAPI::setHome($sender, $args[0], $args[1]);
                            $sender->sendMessage(Teleportation::getConfigReplace("adminsethome_msg_good"));
                        } else $sender->sendMessage(Teleportation::getConfigReplace("adminsethome_exist_home"));
                    } else $sender->sendMessage(Teleportation::getConfigReplace("adminsethome_no_home"));
                } else $sender->sendMessage(Teleportation::getConfigReplace("adminsethome_no_exist_player"));
            } else $sender->sendMessage(Teleportation::getConfigReplace("adminsethome_msg_no_player"));
        }
    }
}