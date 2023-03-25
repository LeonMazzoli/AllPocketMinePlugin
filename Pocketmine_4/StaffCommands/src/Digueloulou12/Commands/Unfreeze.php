<?php

namespace Digueloulou12\Commands;

use pocketmine\command\CommandSender;
use pocketmine\command\Command;
use pocketmine\player\Player;
use Digueloulou12\Staff;
use pocketmine\Server;

class Unfreeze extends Command
{
    public function __construct()
    {
        parent::__construct(Staff::getInstance()->getConfigValue("unfreeze")[0]);
        if (isset(Staff::getInstance()->getConfigValue("unfreeze")[1])) $this->setDescription(Staff::getInstance()->getConfigValue("unfreeze")[1]);
        $this->setAliases(Staff::getInstance()->getConfigValue("unfreeze_aliases"));
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args)
    {
        if (!Staff::getInstance()->hasPermissionPlayer($sender, "unfreeze")) return;

        if (isset($args[0])) {
            $player = Server::getInstance()->getPlayerByPrefix($args[0]);
            if ($player instanceof Player) {
                if ($player->isImmobile()) {
                    $player->setImmobile(false);
                    Staff::getInstance()->sendDiscordMessage($player, $sender, null, "unfreeze_discord");
                    $sender->sendMessage(Staff::getInstance()->getConfigReplace("unfreeze_sender", "{player}", $player->getName()));
                    $player->sendMessage(Staff::getInstance()->getConfigReplace("unfreeze_player", "{player}", $sender->getName()));
                } else $sender->sendMessage(Staff::getInstance()->getConfigReplace("unfreeze_no_freeze"));
            } else $sender->sendMessage(Staff::getInstance()->getConfigReplace("no_online_player"));
        } else $sender->sendMessage(Staff::getInstance()->getConfigReplace("no_args_player"));
    }
}