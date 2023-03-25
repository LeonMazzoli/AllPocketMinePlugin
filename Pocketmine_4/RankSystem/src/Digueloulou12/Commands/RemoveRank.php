<?php

namespace Digueloulou12\Commands;

use pocketmine\command\CommandSender;
use pocketmine\command\Command;
use Digueloulou12\Rank;

class RemoveRank extends Command
{
    public function __construct()
    {
        parent::__construct(Rank::getInstance()->getConfigValue("removerank")[0]);
        if (isset(Rank::getInstance()->getConfigValue("removerank")[1])) $this->setDescription(Rank::getInstance()->getConfigValue("removerank")[1]);
        $this->setAliases(Rank::getInstance()->getConfigValue("removerank_aliases"));
        $this->setPermission("rank.use");
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args)
    {
        if (!$sender->hasPermission("rank.use")) {
            $sender->sendMessage(Rank::getInstance()->getConfigReplace("no_perm"));
            return;
        }

        if (isset($args[0])) {
            if (Rank::getInstance()->existRank($args[0])) {
                if ($args[0] !== Rank::getInstance()->getConfigValue("default_rank")) {
                    Rank::getInstance()->removeRank($args[0]);
                    $sender->sendMessage(Rank::getInstance()->getConfigReplace("removerank_msg", "{rank}", $args[0]));
                } else $sender->sendMessage(Rank::getInstance()->getConfigReplace("no_remove_default_rank"));
            } else $sender->sendMessage(Rank::getInstance()->getConfigReplace("no_exist_rank", "{rank}", $args[0]));
        } else $sender->sendMessage(Rank::getInstance()->getConfigReplace("no_args_rank"));
    }
}