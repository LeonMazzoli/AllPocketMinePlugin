<?php

namespace Digueloulou12\Commands;

use pocketmine\command\CommandSender;
use pocketmine\command\Command;
use Digueloulou12\Rank;

class AddRank extends Command
{
    public function __construct()
    {
        parent::__construct(Rank::getInstance()->getConfigValue("addrank")[0]);
        if (isset(Rank::getInstance()->getConfigValue("addrank")[1])) $this->setDescription(Rank::getInstance()->getConfigValue("addrank")[1]);
        $this->setAliases(Rank::getInstance()->getConfigValue("addrank_aliases"));
        $this->setPermission("rank.use");
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args)
    {
        if (!$sender->hasPermission("rank.use")) {
            $sender->sendMessage(Rank::getInstance()->getConfigReplace("no_perm"));
            return;
        }

        if (isset($args[0])) {
            if (!Rank::getInstance()->existRank($args[0])) {
                Rank::getInstance()->addRank($args[0]);
                $sender->sendMessage(Rank::getInstance()->getConfigReplace("addrank_msg", "{rank}", $args[0]));
            } else $sender->sendMessage(Rank::getInstance()->getConfigReplace("already_exist_rank", "{rank}", $args[0]));
        } else $sender->sendMessage(Rank::getInstance()->getConfigReplace("no_args_rank"));
    }
}