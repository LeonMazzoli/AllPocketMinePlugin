<?php

namespace Digueloulou12\LobbyCore\Commands\Ranks;

use Digueloulou12\LobbyCore\API\RankAPI;
use Digueloulou12\LobbyCore\Utils\Utils;
use pocketmine\command\CommandSender;
use pocketmine\command\Command;
use pocketmine\Server;

class ListRanks extends Command
{
    public function __construct()
    {
        parent::__construct(Utils::getConfigValue("listranks")[0]);
        if (isset(Utils::getConfigValue("listranks")[1])) $this->setDescription(Utils::getConfigValue("listranks")[1]);
        $this->setAliases(Utils::getConfigValue("listranks_aliases"));
        $this->setPermission("rank.use");
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args)
    {
        if (!$sender->hasPermission($this->getPermission()) and !Server::getInstance()->isOp($sender->getName())) return;

        $ranks = implode(", ", RankAPI::getAllRanks());
        $sender->sendMessage(Utils::getConfigReplace("listranks_msg", ["{count}", "{ranks}"], [count(RankAPI::getAllRanks()), $ranks]));
    }
}