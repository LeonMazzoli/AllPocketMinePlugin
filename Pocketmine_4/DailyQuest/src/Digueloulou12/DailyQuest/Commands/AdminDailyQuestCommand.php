<?php

namespace Digueloulou12\DailyQuest\Commands;

use Digueloulou12\DailyQuest\API\DailyQuestAPI;
use Digueloulou12\DailyQuest\Utils\Utils;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;

class AdminDailyQuestCommand extends Command
{
    public function __construct(string $name, string $description = "", array $aliases = [])
    {
        parent::__construct($name, $description, null, $aliases);
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args)
    {
        if ($sender instanceof Player) {
            if ($sender->hasPermission(Utils::getConfigReplace("AdminDailyQuestCommandPermission")) or $sender->getServer()->isOp($sender->getName())) {
                $sender->sendMessage(Utils::getConfigReplace("list_players", "{players}", implode(", ", DailyQuestAPI::getAllPlayerFinish())));
            } else $sender->sendMessage(Utils::getConfigReplace("no_permission"));
        }
    }
}