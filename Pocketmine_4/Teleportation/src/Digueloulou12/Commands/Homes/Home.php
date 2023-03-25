<?php

namespace Digueloulou12\Commands\Homes;

use pocketmine\command\CommandSender;
use Digueloulou12\Teleportation;
use pocketmine\command\Command;
use Digueloulou12\API\HomeAPI;
use pocketmine\player\Player;

class Home extends Command
{
    public function __construct()
    {
        $command = explode(":", Teleportation::getConfigValue("home_cmd"));
        parent::__construct($command[0]);
        if (isset($command[1])) $this->setDescription($command[1]);
        $this->setAliases(Teleportation::getConfigValue("home_aliases"));
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args)
    {
        if ($sender instanceof Player) {
            $command = explode(":", Teleportation::getConfigValue("home_cmd"));
            if ((isset($command[2])) and (Teleportation::hasPermissionPlayer($sender, $command[2]))) return;
            if ((isset($args[0])) and (HomeAPI::existHome($sender, $args[0]))) {
                $sender->teleport(HomeAPI::getHome($sender, $args[0]));
                $sender->sendMessage(Teleportation::getConfigReplace("home_msg_teleport"));
            } else {
                $homes = implode(", ", HomeAPI::getAllHomes($sender));
                $sender->sendMessage(Teleportation::getConfigReplace("home_msg_list", ["{home}"], [$homes]));
            }
        }
    }
}