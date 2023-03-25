<?php

namespace Digueloulou12\Commands;

use pocketmine\command\CommandSender;
use pocketmine\command\Command;
use pocketmine\player\Player;
use Digueloulou12\Rank;
use pocketmine\Server;

class AddPermission extends Command
{
    public function __construct()
    {
        parent::__construct(Rank::getInstance()->getConfigValue("addpermission")[0]);
        if (isset(Rank::getInstance()->getConfigValue("addpermission")[1])) $this->setDescription(Rank::getInstance()->getConfigValue("addpermission")[1]);
        $this->setAliases(Rank::getInstance()->getConfigValue("addpermission_aliases"));
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
                            if (!in_array($args[2], Rank::getInstance()->getPermission($name, true))) {
                                Rank::getInstance()->addPermission($name, $args[2], true);
                                $sender->sendMessage(Rank::getInstance()->getConfigReplace("addpermission_player_msg", ["{player}", "{perm}"], [$name, $args[2]]));
                            } else $sender->sendMessage(Rank::getInstance()->getConfigReplace("has_perm_player", ["{player}", "{perm}"], [$name, $args[2]]));
                        } else $sender->sendMessage(Rank::getInstance()->getConfigReplace("no_args_perm"));
                    } else $sender->sendMessage(Rank::getInstance()->getConfigReplace("no_exist_player", "{player}", $name));
                } else $sender->sendMessage(Rank::getInstance()->getConfigReplace("no_args_player"));
            } elseif ($args[0] === "rank") {
                if (isset($args[1])) {
                    if (Rank::getInstance()->existRank($args[1])) {
                        if (isset($args[2])) {
                            if (!in_array($args[2], Rank::getInstance()->getPermission($args[1], false))) {
                                Rank::getInstance()->addPermission($args[1], $args[2], false);
                                $sender->sendMessage(Rank::getInstance()->getConfigReplace("addpermission_rank_msg", ["{rank}", "{perm}"], [$args[1], $args[2]]));
                            } else $sender->sendMessage(Rank::getInstance()->getConfigReplace("has_perm_rank", ["{rank}", "{perm}"], [$args[2], $args[2]]));
                        } else $sender->sendMessage(Rank::getInstance()->getConfigReplace("no_args_perm"));
                    } else $sender->sendMessage(Rank::getInstance()->getConfigReplace("no_exist_rank", "{rank}", $args[1]));
                } else $sender->sendMessage(Rank::getInstance()->getConfigReplace("no_args_rank"));
            } else $sender->sendMessage(Rank::getInstance()->getConfigReplace("no_args_type"));
        } else $sender->sendMessage(Rank::getInstance()->getConfigReplace("no_args_type"));
    }
}