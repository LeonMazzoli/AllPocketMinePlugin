<?php

namespace Digueloulou12\Commands;

use pocketmine\command\CommandSender;
use pocketmine\command\Command;
use pocketmine\player\Player;
use Digueloulou12\Staff;
use pocketmine\Server;

class Unban extends Command
{
    public function __construct()
    {
        parent::__construct(Staff::getInstance()->getConfigValue("unban")[0]);
        if (isset(Staff::getInstance()->getConfigValue("unban")[1])) $this->setDescription(Staff::getInstance()->getConfigValue("unban")[1]);
        $this->setAliases(Staff::getInstance()->getConfigValue("unban_aliases"));
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args)
    {
        if (!Staff::getInstance()->hasPermissionPlayer($sender, "unban")) return;

        if (isset($args[0])) {
            $player = Server::getInstance()->getPlayerByPrefix($args[0]);
            if ($player instanceof Player) $name = $player->getName(); else $name = $args[0];
            if (Staff::getInstance()->isBannedPlayer($name)) {
                Staff::getInstance()->removeBan($name);
                Staff::getInstance()->sendDiscordMessage($name, $sender, null, "unban_discord");
                $sender->sendMessage(Staff::getInstance()->getConfigReplace("unban_sender", "{player}", $name));
            } else $sender->sendMessage(Staff::getInstance()->getConfigReplace("unban_no_ban"));
        } else $sender->sendMessage(Staff::getInstance()->getConfigReplace("no_args_player"));
    }
}