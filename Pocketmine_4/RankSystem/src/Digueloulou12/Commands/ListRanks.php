<?php

namespace Digueloulou12\Commands;

use pocketmine\command\CommandSender;
use pocketmine\command\Command;
use Digueloulou12\Rank;

class ListRanks extends Command
{
    public function __construct()
    {
        parent::__construct(Rank::getInstance()->getConfigValue("listranks")[0]);
        if (isset(Rank::getInstance()->getConfigValue("listranks")[1])) $this->setDescription(Rank::getInstance()->getConfigValue("listranks")[1]);
        $this->setAliases(Rank::getInstance()->getConfigValue("listranks_aliases"));
        $this->setPermission("rank.use");
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args)
    {
        if (!$sender->hasPermission("rank.use")) {
            $sender->sendMessage(Rank::getInstance()->getConfigReplace("no_perm"));
            return;
        }

        $ranks = implode(", ", Rank::getInstance()->getAllRanks());
        $sender->sendMessage(Rank::getInstance()->getConfigReplace("listranks_msg", ["{count}", "{ranks}"], [count(Rank::getInstance()->getAllRanks()), $ranks]));
    }
}