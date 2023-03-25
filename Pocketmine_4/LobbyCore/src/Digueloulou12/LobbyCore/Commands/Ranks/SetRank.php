<?php

namespace Digueloulou12\LobbyCore\Commands\Ranks;

use Digueloulou12\LobbyCore\API\RankAPI;
use Digueloulou12\LobbyCore\Utils\Utils;
use pocketmine\command\CommandSender;
use pocketmine\command\Command;
use pocketmine\player\Player;
use pocketmine\Server;

class SetRank extends Command
{
    public function __construct()
    {
        parent::__construct(Utils::getConfigValue("setrank")[0]);
        if (isset(Utils::getConfigValue("setrank")[1])) $this->setDescription(Utils::getConfigValue("setrank")[1]);
        $this->setAliases(Utils::getConfigValue("setrank_aliases"));
        $this->setPermission("rank.use");
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args)
    {
        if (!$sender->hasPermission($this->getPermission()) and !Server::getInstance()->isOp($sender->getName())) return;

        if (isset($args[0])) {
            $player = Server::getInstance()->getPlayerByPrefix($args[0]);
            if ($player instanceof Player) $name = $player->getName(); else $name = $args[0];
            if (RankAPI::existPlayer($name)) {
                if (isset($args[1])) {
                    if (RankAPI::existRank($args[1])) {
                        RankAPI::setRank($name, $args[1]);
                        $sender->sendMessage(Utils::getConfigReplace("setrank_msg", ["{player}", "{rank}"], [$name, $args[1]]));
                    } else $sender->sendMessage(Utils::getConfigReplace("no_exist_rank", "{rank}", $args[1]));
                } else $sender->sendMessage(Utils::getConfigReplace("no_args_rank"));
            } else $sender->sendMessage(Utils::getConfigReplace("no_exist_player", "{player}", $args[0]));
        } else $sender->sendMessage(Utils::getConfigReplace("no_args_player"));
    }
}