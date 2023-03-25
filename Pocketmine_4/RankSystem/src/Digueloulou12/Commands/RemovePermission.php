<?php

namespace Digueloulou12\Commands;

use pocketmine\command\CommandSender;
use pocketmine\command\Command;
use pocketmine\player\Player;
use Digueloulou12\Rank;
use pocketmine\Server;

class RemovePermission extends Command
{
    public function __construct()
    {
        parent::__construct(Rank::getInstance()->getConfigValue("removepermission")[0]);
        if (isset(Rank::getInstance()->getConfigValue("removepermission")[1])) $this->setDescription(Rank::getInstance()->getConfigValue("removepermission")[1]);
        $this->setAliases(Rank::getInstance()->getConfigValue("removepermission_aliases"));
        $this->setPermission("rank.use");
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args)
    {
        if (!$sender->hasPermission("rank.use")) {
            $sender->sendMessage(Rank::getInstance()->getConfigReplace("no_perm"));
            return;
        }

        if (isset($args[0])) {
            if ($args[0] === "player") {
                if (isset($args[1])) {
                    $player = Server::getInstance()->getPlayerByPrefix($args[1]);
                    if ($player instanceof Player) $name = $player->getName(); else $name = $args[1];
                    if (Rank::getInstance()->existPlayer($name)) {
                        if (isset($args[2])) {
                            if (in_array($args[2], Rank::getInstance()->getPermission($name, true))) {
                                Rank::getInstance()->removePermission($name, $args[2], true);
                                $sender->sendMessage(Rank::getInstance()->getConfigReplace("removepermission_player_msg", ["{player}", "{perm}"], [$name, $args[2]]));
                            } else $sender->sendMessage(Rank::getInstance()->getConfigReplace("no_has_perm_player", ["{player}", "{perm}"], [$name, $args[2]]));
                        } else $sender->sendMessage(Rank::getInstance()->getConfigReplace("no_args_perm"));
                    } else $sender->sendMessage(Rank::getInstance()->getConfigReplace("no_exist_player", "{player}", $name));
                } else $sender->sendMessage(Rank::getInstance()->getConfigReplace("no_args_player"));
            } elseif ($args[0] === "rank") {
                if (isset($args[1])) {
                    if (Rank::getInstance()->existRank($args[1])) {
                        if (isset($args[2])) {
                            if (in_array($args[2], Rank::getInstance()->getPermission($args[1], false))) {
                                Rank::getInstance()->removePermission($args[1], $args[2], false);
                                $sender->sendMessage(Rank::getInstance()->getConfigReplace("removepermission_rank_msg", ["{rank}", "{perm}"], [$args[1], $args[2]]));
                            } else $sender->sendMessage(Rank::getInstance()->getConfigReplace("no_has_perm_rank", ["{rank}", "{perm}"], [$args[2], $args[2]]));
                        } else $sender->sendMessage(Rank::getInstance()->getConfigReplace("no_args_perm"));
                    } else $sender->sendMessage(Rank::getInstance()->getConfigReplace("no_exist_rank", "{rank}", $args[1]));
                } else $sender->sendMessage(Rank::getInstance()->getConfigReplace("no_args_rank"));
            } else $sender->sendMessage(Rank::getInstance()->getConfigReplace("no_args_type"));
        } else $sender->sendMessage(Rank::getInstance()->getConfigReplace("no_args_type"));
    }
}