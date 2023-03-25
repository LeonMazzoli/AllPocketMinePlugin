<?php

namespace Digueloulou12\DailyQuest\Commands;

use Digueloulou12\DailyQuest\API\DailyQuestAPI;
use Digueloulou12\DailyQuest\API\DailyQuestType;
use Digueloulou12\DailyQuest\Form\DailyQuestForm;
use Digueloulou12\DailyQuest\Utils\Utils;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;

class DailyQuestCommand extends Command
{
    public function __construct(string $name, string $description = "", array $aliases = [])
    {
        parent::__construct($name, $description, null, $aliases);
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args)
    {
        if ($sender instanceof Player) {
            if (DailyQuestAPI::isStartQuest($sender)) {
                if (DailyQuestAPI::getTypeOfQuest() === DailyQuestType::ITEM) {
                    $item = DailyQuestAPI::getBlockItemOfQuest();
                    if ($sender->getInventory()->contains($item)) {
                        $sender->getInventory()->removeItem($item);
                        DailyQuestAPI::finishQuest($sender);
                    }
                }
            }

            if (!DailyQuestAPI::hasAlreadyMakeQuest($sender)) {
                $sender->sendForm(DailyQuestForm::dailyQuestForm());
            } else $sender->sendMessage(Utils::getConfigReplace("already_finish"));
        }
    }
}