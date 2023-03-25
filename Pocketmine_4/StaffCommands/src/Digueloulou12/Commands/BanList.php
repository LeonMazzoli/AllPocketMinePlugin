<?php

namespace Digueloulou12\Commands;

use Digueloulou12\Staff;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;

class BanList extends Command
{
    public function __construct()
    {
        parent::__construct(Staff::getInstance()->getConfigValue("banlist")[0]);
        if (isset(Staff::getInstance()->getConfigValue("banlist")[1])) $this->setDescription(Staff::getInstance()->getConfigValue("banlist")[1]);
        $this->setAliases(Staff::getInstance()->getConfigValue("banlist_aliases"));
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args)
    {
        if (!Staff::getInstance()->hasPermissionPlayer($sender, "banlist")) return;

        $banned = [];
        foreach (Staff::getInstance()->getAllBan() as $name => $value) {
            $banned[] = $name;
        }

        $sender->sendMessage(Staff::getInstance()->getConfigReplace("banlist_msg", "{bans}", implode(", ", $banned)));
    }
}