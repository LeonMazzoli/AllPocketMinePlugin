<?php

namespace Digueloulou12\Commands;

use pocketmine\command\CommandSender;
use pocketmine\command\Command;
use pocketmine\player\Player;
use Digueloulou12\Staff;
use pocketmine\Server;

class Unmute extends Command
{
    public function __construct()
    {
        parent::__construct(Staff::getInstance()->getConfigValue("unmute")[0]);
        if (isset(Staff::getInstance()->getConfigValue("unmute")[1])) $this->setDescription(Staff::getInstance()->getConfigValue("unmute")[1]);
        $this->setAliases(Staff::getInstance()->getConfigValue("unmute_aliases"));
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args)
    {
        if (!Staff::getInstance()->hasPermissionPlayer($sender, "unmute")) return;

        if (isset($args[0])) {
            $player = Server::getInstance()->getPlayerByPrefix($args[0]);
            if ($player instanceof Player) $name = $player->getName(); else $name = $args[0];
            if (isset(Mute::$mute[$name])) {
                unset(Mute::$mute[$name]);
                $sender->sendMessage(Staff::getInstance()->getConfigReplace("unmute_sender", "{player}", $name));
                Staff::getInstance()->sendDiscordMessage($name, $sender, null, "unmute_discord");
                if ($player instanceof Player) $player->sendMessage(Staff::getInstance()->getConfigReplace("unmute_player", "{player}", $sender->getName()));
            } else $sender->sendMessage(Staff::getInstance()->getConfigReplace("unmute_no_mute"));
        } else $sender->sendMessage(Staff::getInstance()->getConfigReplace("no_args_player"));
    }
}