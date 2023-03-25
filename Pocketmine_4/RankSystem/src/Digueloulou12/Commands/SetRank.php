<?php

namespace Digueloulou12\Commands;

use Digueloulou12\Events\RankEvents;
use pocketmine\command\CommandSender;
use pocketmine\command\Command;
use pocketmine\player\Player;
use Digueloulou12\Rank;
use pocketmine\Server;

class SetRank extends Command
{
    public function __construct()
    {
        parent::__construct(Rank::getInstance()->getConfigValue("setrank")[0]);
        if (isset(Rank::getInstance()->getConfigValue("setrank")[1])) $this->setDescription(Rank::getInstance()->getConfigValue("setrank")[1]);
        $this->setAliases(Rank::getInstance()->getConfigValue("setrank_aliases"));
        $this->setPermission("rank.use");
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args)
    {
        if (!$sender->hasPermission("rank.use")) {
            $sender->sendMessage(Rank::getInstance()->getConfigReplace("no_perm"));
            return;
        }

        if (isset($args[0])) {
            $player = Server::getInstance()->getPlayerByPrefix($args[0]);
            if ($player instanceof Player) $name = $player->getName(); else $name = $args[0];
            if (Rank::getInstance()->existPlayer($name)) {
                if (isset($args[1])) {
                    if (Rank::getInstance()->existRank($args[1])) {
                        Rank::getInstance()->setRank($name, $args[1]);
                        $sender->sendMessage(Rank::getInstance()->getConfigReplace("setrank_msg", ["{player}", "{rank}"], [$name, $args[1]]));
                        if ($player instanceof Player) RankEvents::updateNameTag($player);
                    } else $sender->sendMessage(Rank::getInstance()->getConfigReplace("no_exist_rank", "{rank}", $args[1]));
                } else $sender->sendMessage(Rank::getInstance()->getConfigReplace("no_args_rank"));
            } else $sender->sendMessage(Rank::getInstance()->getConfigReplace("no_exist_player", "{player}", $args[0]));
        } else $sender->sendMessage(Rank::getInstance()->getConfigReplace("no_args_player"));
    }
}