<?php

namespace Digueloulou12\Commands;

use pocketmine\command\CommandSender;
use pocketmine\command\Command;
use pocketmine\player\Player;
use Digueloulou12\Staff;
use pocketmine\Server;

class Mute extends Command
{
    public static array $mute = [];

    public function __construct()
    {
        parent::__construct(Staff::getInstance()->getConfigValue("mute")[0]);
        if (isset(Staff::getInstance()->getConfigValue("mute")[1])) $this->setDescription(Staff::getInstance()->getConfigValue("mute")[1]);
        $this->setAliases(Staff::getInstance()->getConfigValue("mute_aliases"));
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args)
    {
        if (!Staff::getInstance()->hasPermissionPlayer($sender, "mute")) return;

        if (isset($args[0])) {
            $player = Server::getInstance()->getPlayerByPrefix($args[0]);
            if ($player instanceof Player) {
                if (isset($args[1])) {
                    if (ctype_alnum($args[1])) {
                        if (Staff::getInstance()->getTime($args[1]) !== null) {
                            if (isset($args[2])) {
                                if (!isset(self::$mute[$player->getName()])) {
                                    $reason = "";
                                    for ($i = 2; $i < count($args); $i++) {
                                        $reason .= $args[$i];
                                        $reason .= " ";
                                    }
                                    self::$mute[$player->getName()] = time() + Staff::getInstance()->getTime($args[1]);
                                    Server::getInstance()->broadcastMessage(Staff::getInstance()->getConfigReplace("mute_all", ["{player}", "{moderator}", "{time}", "{reason}"], [$player->getName(), $sender->getName(), $args[1], $reason]));
                                    Staff::getInstance()->sendDiscordMessage($player, $sender, $reason, "mute_discord", $args[1]);
                                    $sender->sendMessage(Staff::getInstance()->getConfigReplace("mute_sender", ["{reason}", "{time}", "{player}"], [$reason, $args[1], $player->getName()]));
                                    $player->sendMessage(Staff::getInstance()->getConfigReplace("mute_player", ["{reason}", "{time}", "{player}"], [$reason, $args[1], $sender->getName()]));
                                } else $sender->sendMessage(Staff::getInstance()->getConfigReplace("mute_already"));
                            } else $sender->sendMessage(Staff::getInstance()->getConfigReplace("no_args_reason"));
                        } else $sender->sendMessage(Staff::getInstance()->getConfigReplace("no_valid_args_time"));
                    } else $sender->sendMessage(Staff::getInstance()->getConfigReplace("no_valid_args_time"));
                } else $sender->sendMessage(Staff::getInstance()->getConfigReplace("no_args_time"));
            } else $sender->sendMessage(Staff::getInstance()->getConfigReplace("no_online_player"));
        } else $sender->sendMessage(Staff::getInstance()->getConfigReplace("no_args_player"));
    }
}