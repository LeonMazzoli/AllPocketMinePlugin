<?php

namespace Digueloulou12\LobbyCore\Commands\Ranks;

use Digueloulou12\LobbyCore\API\RankAPI;
use Digueloulou12\LobbyCore\Utils\Utils;
use pocketmine\command\CommandSender;
use pocketmine\command\Command;
use pocketmine\Server;

class RemoveRank extends Command
{
    public function __construct()
    {
        parent::__construct(Utils::getConfigValue("removerank")[0]);
        if (isset(Utils::getConfigValue("removerank")[1])) $this->setDescription(Utils::getConfigValue("removerank")[1]);
        $this->setAliases(Utils::getConfigValue("removerank_aliases"));
        $this->setPermission("rank.use");
    }

    /**
     * @throws \JsonException
     */
    public function execute(CommandSender $sender, string $commandLabel, array $args)
    {
        if (!$sender->hasPermission($this->getPermission()) and !Server::getInstance()->isOp($sender->getName())) return;

        if (isset($args[0])) {
            if (RankAPI::existRank($args[0])) {
                RankAPI::removeRank($args[0]);
                $sender->sendMessage(Utils::getConfigReplace("removerank_msg", "{rank}", $args[0]));
            } else $sender->sendMessage(Utils::getConfigReplace("no_exist_rank", "{rank}", $args[0]));
        } else $sender->sendMessage(Utils::getConfigReplace("no_args_rank"));
    }
}