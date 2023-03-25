<?php

namespace Digueloulou12\Commands;

use pocketmine\command\CommandSender;
use pocketmine\command\Command;
use pocketmine\player\Player;
use Digueloulou12\Staff;
use pocketmine\Server;

class Ban extends Command
{
    public function __construct()
    {
        parent::__construct(Staff::getInstance()->getConfigValue("ban")[0]);
        if (isset(Staff::getInstance()->getConfigValue("ban")[1])) $this->setDescription(Staff::getInstance()->getConfigValue("ban")[1]);
        $this->setAliases(Staff::getInstance()->getConfigValue("ban_aliases"));
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args)
    {
        if (!Staff::getInstance()->hasPermissionPlayer($sender, "ban")) return;

        if (isset($args[0])) {
            $player = Server::getInstance()->getPlayerByPrefix($args[0]);
            if ($player instanceof Player) $name = $player->getName(); else $name = $args[0];
            if (isset($args[1])) {
                if (ctype_alnum($args[1])) {
                    if (Staff::getInstance()->getTime($args[1]) !== null) {
                        if (isset($args[2])) {
                            if (!Staff::getInstance()->isBannedPlayer($name)) {
                                $reason = "";
                                for ($i = 2; $i < count($args); $i++) {
                                    $reason .= $args[$i];
                                    $reason .= " ";
                                }
                                Server::getInstance()->broadcastMessage(Staff::getInstance()->getConfigReplace("ban_all", ["{player}", "{moderator}", "{time}", "{reason}"], [$name, $sender->getName(), $args[1], $reason]));
                                Staff::getInstance()->addBan($name, $sender, $reason, Staff::getInstance()->getTime($args[1]));
                                Staff::getInstance()->sendDiscordMessage($name, $sender, $reason, "ban_discord", $args[1]);
                                $sender->sendMessage(Staff::getInstance()->getConfigReplace("ban_sender", ["{player}", "{time}", "{reason}"], [$name, $args[1], $reason]));
                            } else $sender->sendMessage(Staff::getInstance()->getConfigReplace("ban_already"));
                        } else $sender->sendMessage(Staff::getInstance()->getConfigReplace("no_args_reason"));
                    } else $sender->sendMessage(Staff::getInstance()->getConfigReplace("no_valid_args_time"));
                } else $sender->sendMessage(Staff::getInstance()->getConfigReplace("no_valid_args_time"));
            } else $sender->sendMessage(Staff::getInstance()->getConfigReplace("no_args_time"));
        } else $sender->sendMessage(Staff::getInstance()->getConfigReplace("no_args_player"));
    }
}