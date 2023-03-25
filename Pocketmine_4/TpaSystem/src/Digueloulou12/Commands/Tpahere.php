<?php

namespace Digueloulou12\Commands;

use pocketmine\command\CommandSender;
use pocketmine\command\Command;
use pocketmine\player\Player;
use Digueloulou12\TpaSystem;
use pocketmine\Server;

class Tpahere extends Command
{
    public function __construct()
    {
        $command = explode(":", TpaSystem::getConfigValue("tpahere_cmd"));
        parent::__construct($command[0]);
        if (isset($command[1])) $this->setDescription($command[1]);
        $this->setAliases(TpaSystem::getConfigValue("tpahere_aliases"));
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args)
    {
        if ($sender instanceof Player) {
            $command = explode(":", TpaSystem::getConfigValue("tpahere_cmd"));
            if ((isset($command[2])) and (TpaSystem::hasPermissionPlayer($sender, $command[2]))) return;
            if (isset($args[0])) {
                $player = Server::getInstance()->getPlayerByPrefix($args[0]);
                if ($player instanceof Player) {
                    Tpa::$invitation[$player->getName()] = ["time" => time() + TpaSystem::getConfigValue("tpa_time_invitation"), "player" => $sender->getName(), "here" => true];
                    $player->sendMessage(TpaSystem::getConfigReplace("tpahere_msg_player", ["{player}"], [$sender->getName()]));
                    $sender->sendMessage(TpaSystem::getConfigReplace("tpahere_msg_sender", ["{player}"], [$player->getName()]));
                } else $sender->sendMessage(TpaSystem::getConfigReplace("trahere_no_online_player"));
            } else $sender->sendMessage(TpaSystem::getConfigReplace("tpahere_no_player"));
        }
    }
}