<?php

namespace Digueloulou12\Commands\Tpa;

use pocketmine\command\CommandSender;
use Digueloulou12\Teleportation;
use pocketmine\command\Command;
use pocketmine\player\Player;
use pocketmine\Server;

class Tpaccept extends Command
{
    public function __construct()
    {
        $command = explode(":", Teleportation::getConfigValue("tpaccept_cmd"));
        parent::__construct($command[0]);
        if (isset($command[1])) $this->setDescription($command[1]);
        $this->setAliases(Teleportation::getConfigValue("tpaccept_aliases"));
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args)
    {
        if ($sender instanceof Player) {
            $command = explode(":", Teleportation::getConfigValue("tpaccept_cmd"));
            if ((isset($command[2])) and (Teleportation::hasPermissionPlayer($sender, $command[2]))) return;
            if (!empty(Tpa::$invitation[$sender->getName()])) {
                if (Tpa::$invitation[$sender->getName()]["time"] > time()) {
                    $player = Server::getInstance()->getPlayerByPrefix(Tpa::$invitation[$sender->getName()]["player"]);
                    if ($player instanceof Player) {
                        if (Tpa::$invitation[$sender->getName()]["here"]) {
                            $sender->teleport($player->getPosition());
                        } else $player->teleport($sender->getPosition());
                        unset(Tpa::$invitation[$sender->getName()]);
                    } else $sender->sendMessage(Teleportation::getConfigReplace("tpaccept_no_online"));
                } else $sender->sendMessage(Teleportation::getConfigReplace("tpaccept_expired"));
            } else $sender->sendMessage(Teleportation::getConfigReplace("tpaccept_no_invitation"));
        }
    }
}