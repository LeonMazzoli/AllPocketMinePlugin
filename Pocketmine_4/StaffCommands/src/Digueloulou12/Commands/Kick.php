<?php

namespace Digueloulou12\Commands;

use pocketmine\command\CommandSender;
use pocketmine\command\Command;
use pocketmine\player\Player;
use Digueloulou12\Staff;
use pocketmine\Server;

class Kick extends Command
{
    public function __construct()
    {
        parent::__construct(Staff::getInstance()->getConfigValue("kick")[0]);
        if (isset(Staff::getInstance()->getConfigValue("kick")[1])) $this->setDescription(Staff::getInstance()->getConfigValue("kick")[1]);
        $this->setAliases(Staff::getInstance()->getConfigValue("kick_aliases"));
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args)
    {
        if (!Staff::getInstance()->hasPermissionPlayer($sender, "kick")) return;

        if (isset($args[0])) {
            $player = Server::getInstance()->getPlayerByPrefix($args[0]);
            if ($player instanceof Player) {
                if (isset($args[1])) {
                    $reason = "";
                    for ($i = 1; $i < count($args); $i++) {
                        $reason .= $args[$i];
                        $reason .= " ";
                    }
                    Staff::getInstance()->sendDiscordMessage($player, $sender, $reason, "kick_discord");
                    $player->kick(Staff::getInstance()->getConfigReplace("kick_kick", ["{player}", "{reason}"], [$sender->getName(), $reason]));
                    $sender->sendMessage(Staff::getInstance()->getConfigReplace("kick_sender", ["{player}", "{reason}"], [$player->getName(), $reason]));
                } else $sender->sendMessage(Staff::getInstance()->getConfigReplace("no_args_reason"));
            } else $sender->sendMessage(Staff::getInstance()->getConfigReplace("no_online_player"));
        } else $sender->sendMessage(Staff::getInstance()->getConfigReplace("no_args_player"));
    }
}