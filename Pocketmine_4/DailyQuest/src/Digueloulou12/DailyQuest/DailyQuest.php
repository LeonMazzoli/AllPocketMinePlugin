<?php

namespace Digueloulou12\DailyQuest;

use Digueloulou12\DailyQuest\API\DailyQuestAPI;
use Digueloulou12\DailyQuest\Commands\AdminDailyQuestCommand;
use Digueloulou12\DailyQuest\Commands\DailyQuestCommand;
use Digueloulou12\DailyQuest\Events\DailyQuestEvents;
use Digueloulou12\DailyQuest\Task\DailyQuestTask;
use Digueloulou12\DailyQuest\Utils\Utils;
use pocketmine\plugin\PluginBase;

class DailyQuest extends PluginBase
{
    private static self $this;

    public function onEnable(): void
    {
        self::$this = $this;
        $this->saveDefaultConfig();

        new DailyQuestAPI();

        $this->getServer()->getCommandMap()->registerAll("DailyQuestCommands", [
            new DailyQuestCommand(
                Utils::getConfigValue("DailyQuestCommand")[0] ?? "dailyquest",
                Utils::getConfigValue("DailyQuestCommand")[1] ?? "",
                Utils::getConfigValue("DailyQuestCommandAliases") ?? []
            ),

            new AdminDailyQuestCommand(
                Utils::getConfigValue("AdminDailyQuestCommand")[0] ?? "admindailyquest",
                Utils::getConfigValue("AdminDailyQuestCommand")[1] ?? "",
                Utils::getConfigValue("AdminDailyQuestCommandAliases") ?? []
            )
        ]);

        $this->getScheduler()->scheduleRepeatingTask(new DailyQuestTask(), 20 * 3600);
        $this->getServer()->getPluginManager()->registerEvents(new DailyQuestEvents(), $this);
    }

    public static function getInstance(): self
    {
        return self::$this;
    }

    public function onDisable(): void
    {
        DailyQuestAPI::save();
    }
}