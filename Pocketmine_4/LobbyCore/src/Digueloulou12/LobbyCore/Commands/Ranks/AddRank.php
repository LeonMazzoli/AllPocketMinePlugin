<?php

namespace Digueloulou12\LobbyCore\Commands\Ranks;

use Digueloulou12\LobbyCore\API\RankAPI;
use Digueloulou12\LobbyCore\Utils\Utils;
use pocketmine\command\CommandSender;
use pocketmine\command\Command;
use pocketmine\Server;

class AddRank extends Command
{
    public function __construct()
    {
        parent::__construct(Utils::getConfigValue("addrank")[0]);
        if (isset(Utils::getConfigValue("addrank")[1])) $this->setDescription(Utils::getConfigValue("addrank")[1]);
        $this->setAliases(Utils::getConfigValue("addrank_aliases"));
        $this->setPermission("rank.use");
    }

    /**
     * @throws \JsonException
     */
    public function execute(CommandSender $sender, string $commandLabel, array $args)
    {
        if (!$sender->hasPermission($this->getPermission()) and !Server::getInstance()->isOp($sender->getName())) return;

        if (isset($args[0])) {
            if (!RankAPI::existRank($args[0])) {
                RankAPI::addRank($args[0]);
                $sender->sendMessage(Utils::getConfigReplace("addrank_msg", "{rank}", $args[0]));
            } else $sender->sendMessage(Utils::getConfigReplace("already_exist_rank", "{rank}", $args[0]));
        } else $sender->sendMessage(Utils::getConfigReplace("no_args_rank"));
    }
}