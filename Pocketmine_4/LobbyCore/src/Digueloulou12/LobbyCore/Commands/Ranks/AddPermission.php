<?php

namespace Digueloulou12\LobbyCore\Commands\Ranks;

use Digueloulou12\LobbyCore\API\RankAPI;
use Digueloulou12\LobbyCore\Utils\Utils;
use JsonException;
use pocketmine\command\CommandSender;
use pocketmine\command\Command;
use pocketmine\player\Player;
use pocketmine\Server;

class AddPermission extends Command
{
    public function __construct()
    {
        parent::__construct(Utils::getConfigValue("addpermission")[0]);
        if (isset(Utils::getConfigValue("addpermission")[1])) $this->setDescription(Utils::getConfigValue("addpermission")[1]);
        $this->setAliases(Utils::getConfigValue("addpermission_aliases"));
        $this->setPermission("rank.use");
    }

    /**
     * @throws JsonException
     */
    public function execute(CommandSender $sender, string $commandLabel, array $args)
    {
        if (!$sender->hasPermission($this->getPermission()) and !Server::getInstance()->isOp($sender->getName())) return;

        if (isset($args[0])) {
            if ($args[0] === "player") {
                if (isset($args[1])) {
                    $player = Server::getInstance()->getPlayerByPrefix($args[1]);
                    if ($player instanceof Player) $name = $player->getName(); else $name = $args[1];
                    if (RankAPI::existPlayer($name)) {
                        if (isset($args[2])) {
                            if (!in_array($args[2], RankAPI::getPermission($name, true))) {
                                RankAPI::addPermission($name, $args[2], true);
                                $sender->sendMessage(Utils::getConfigReplace("addpermission_player_msg", ["{player}", "{perm}"], [$name, $args[2]]));
                            } else $sender->sendMessage(Utils::getConfigReplace("has_perm_player", ["{player}", "{perm}"], [$name, $args[2]]));
                        } else $sender->sendMessage(Utils::getConfigReplace("no_args_perm"));
                    } else $sender->sendMessage(Utils::getConfigReplace("no_exist_player", "{player}", $name));
                } else $sender->sendMessage(Utils::getConfigReplace("no_args_player"));
            } elseif ($args[0] === "rank") {
                if (isset($args[1])) {
                    if (RankAPI::existRank($args[1])) {
                        if (isset($args[2])) {
                            if (!in_array($args[2], RankAPI::getPermission($args[1], false))) {
                                RankAPI::addPermission($args[1], $args[2], false);
                                $sender->sendMessage(Utils::getConfigReplace("addpermission_rank_msg", ["{rank}", "{perm}"], [$args[1], $args[2]]));
                            } else $sender->sendMessage(Utils::getConfigReplace("has_perm_rank", ["{rank}", "{perm}"], [$args[2], $args[2]]));
                        } else $sender->sendMessage(Utils::getConfigReplace("no_args_perm"));
                    } else $sender->sendMessage(Utils::getConfigReplace("no_exist_rank", "{rank}", $args[1]));
                } else $sender->sendMessage(Utils::getConfigReplace("no_args_rank"));
            } else $sender->sendMessage(Utils::getConfigReplace("no_args_type"));
        } else $sender->sendMessage(Utils::getConfigReplace("no_args_type"));
    }
}