<?php

namespace Digueloulou12\DailyQuest\Form;

use Digueloulou12\DailyQuest\API\DailyQuestAPI;
use Digueloulou12\DailyQuest\libs\jojoe77777\FormAPI\SimpleForm;
use Digueloulou12\DailyQuest\Utils\Utils;
use pocketmine\player\Player;

class DailyQuestForm
{
    public static function dailyQuestForm(): SimpleForm
    {
        $form = new SimpleForm(function (Player $player, int $data = null) {
            if (is_null($data)) return;

            if (!DailyQuestAPI::isStartQuest($player)) {
                DailyQuestAPI::startQuest($player);
                $player->sendMessage(Utils::getConfigReplace("start_quest", "{name}", DailyQuestAPI::getNameOfQuest()));
            } else $player->sendMessage(Utils::getConfigReplace("already_start"));
        });
        $form->setTitle(Utils::getConfigReplace("title"));
        $form->setContent(str_replace(["{prefix}", "{name}"], [Utils::getConfigValue("prefix"), DailyQuestAPI::getNameOfQuest()], DailyQuestAPI::getDescriptionOfQuest()));
        $form->addButton(Utils::getConfigReplace("button"));
        return $form;
    }
}