<?php

namespace Digueloulou12\Commands\Tpa;

use pocketmine\command\CommandSender;
use Digueloulou12\Teleportation;
use pocketmine\command\Command;
use pocketmine\player\Player;
use pocketmine\Server;

class Tpahere extends Command
{
    public function __construct()
    {
        $command = explode(":", Teleportation::getConfigValue("tpahere_cmd"));
        parent::__construct($command[0]);
        if (isset($command[1])) $this->setDescription($command[1]);
        $this->setAliases(Teleportation::getConfigValue("tpahere_aliases"));
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args)
    {
        if ($sender instanceof Player) {
            $command = explode(":", Teleportation::getConfigValue("tpahere_cmd"));
            if ((isset($command[2])) and (Teleportation::hasPermissionPlayer($sender, $command[2]))) return;

            if (!in_array($sender->getWorld()->getDisplayName(), Teleportation::getConfigValue("disabled-worlds"))) {
                if (isset($args[0])) {
                    $player = Server::getInstance()->getPlayerByPrefix($args[0]);
                    if ($player instanceof Player) {
                        Tpa::$invitation[$player->getName()] = ["time" => time() + Teleportation::getConfigValue("tpa_time_invitation"), "player" => $sender->getName(), "here" => true];
                        $player->sendMessage(Teleportation::getConfigReplace("tpahere_msg_player", ["{player}"], [$sender->getName()]));
                        $sender->sendMessage(Teleportation::getConfigReplace("tpahere_msg_sender", ["{player}"], [$player->getName()]));
                    } else $sender->sendMessage(Teleportation::getConfigReplace("trahere_no_online_player"));
                } else $sender->sendMessage(Teleportation::getConfigReplace("tpahere_no_player"));
            } else $sender->sendMessage(Teleportation::getConfigReplace("tpa_no_world"));
        }
    }
}