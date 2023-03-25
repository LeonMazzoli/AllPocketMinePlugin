<?php

namespace Digueloulou12\DailyQuest\API;

use Digueloulou12\DailyQuest\DailyQuest;
use Digueloulou12\DailyQuest\Utils\Utils;
use JetBrains\PhpStorm\Pure;
use JsonException;
use pocketmine\block\Block;
use pocketmine\block\BlockFactory;
use pocketmine\console\ConsoleCommandSender;
use pocketmine\item\Item;
use pocketmine\item\ItemFactory;
use pocketmine\player\Player;
use pocketmine\utils\Config;

class DailyQuestAPI
{
    private static array $players = [];
    private static array $quests = [];
    private static Config $questData;
    private static int $quest;

    public function __construct()
    {
        if (!file_exists(DailyQuest::getInstance()->getDataFolder() . "QuestData.json")) {
            self::$questData = new Config(DailyQuest::getInstance()->getDataFolder() . "QuestData.json", Config::JSON, [
                "quest" => 0,
                "players" => [],
                "status" => []
            ]);
        } else self::$questData = new Config(DailyQuest::getInstance()->getDataFolder() . "QuestData.json", Config::JSON);

        foreach (Utils::getConfigValue("quests") as $name => $value) {
            self::$quests[] = ["name" => $name, "description" => $value["description"] ?? "", "type" => $value["type"] ?? [DailyQuestType::BREAK, 1, 0, 64], "win" => $value["win"] ?? ""];
        }

        self::$quest = self::$questData->get("quest");
    }

    public static function hasAlreadyMakeQuest(Player $player): bool
    {
        return in_array($player->getName(), self::$questData->get("players"));
    }

    public static function updateQuest(): void
    {
        self::$quest += 1;
        self::$questData->set("quest", self::$quest);
        self::$questData->set("players", []);
        self::$questData->set("status", []);
    }

    #[Pure] public static function isStartQuest(Player $player): bool
    {
        return in_array($player->getName(), self::$players);
    }

    public static function startQuest(Player $player): void
    {
        self::$players[] = $player->getName();
        self::$questData->setNested("status.{$player->getName()}", 0);
    }

    public static function updatePlayer(Player $player, int $count = 1): void
    {
        self::$questData->setNested("status.{$player->getName()}", self::$questData->get("status")[$player->getName()] + $count);
        $player->sendTip(self::getStatusPlayer($player));
        if (self::$questData->get("status")[$player->getName()] >= self::getCountOfQuest()) self::finishQuest($player);
    }

    public static function getStatusPlayer(Player $player): string
    {
        $min = self::$questData->get("status")[$player->getName()] ?? 0;
        $max = self::getCountOfQuest();
        return Utils::getConfigReplace("status", ["{min}", "{max}"], [$min, $max]);
    }

    public static function finishQuest(Player $player): void
    {
        $array = self::$questData->get("players");
        $array[] = $player->getName();
        self::$questData->set("players", $array);

        unset(self::$players[array_search($player->getName(), self::$players)]);

        self::$questData->removeNested("status.{$player->getName()}");

        $player->getServer()->dispatchCommand(new ConsoleCommandSender($player->getServer(), $player->getServer()->getLanguage()), str_replace("{player}", $player->getName(), self::getWinOfQuest()));
        $player->sendMessage(Utils::getConfigReplace("finish_quest"));
    }

    #[Pure] public static function getDescriptionOfQuest(): string
    {
        return self::getAllQuests()[self::$quest]["description"] ?? self::getAllQuests()[0]["description"];
    }

    #[Pure] public static function getNameOfQuest(): string
    {
        return self::getAllQuests()[self::$quest]["name"] ?? self::getAllQuests()[0]["name"];
    }

    #[Pure] public static function getCountOfQuest(): int
    {
        return self::getAllQuests()[self::$quest]["type"][3] ?? self::getAllQuests()[0]["type"][3];
    }

    #[Pure] public static function getWinOfQuest(): string
    {
        return self::getAllQuests()[self::$quest]["win"] ?? self::getAllQuests()[0]["win"];
    }

    #[Pure] public static function getTypeOfQuest(): string
    {
        return strtolower(self::getAllQuests()[self::$quest]["type"][0] ?? self::getAllQuests()[0]["type"][0]);
    }

    public static function getBlockItemOfQuest(): Block|Item|null
    {
        $id = self::getAllQuests()[self::$quest]["type"][1] ?? 1;
        $meta = self::getAllQuests()[self::$quest]["type"][2] ?? 0;
        if (in_array(self::getTypeOfQuest(), [DailyQuestType::CRAFT, DailyQuestType::ITEM])) {
            $count = self::getAllQuests()[self::$quest]["type"][3] ?? 64;
            return ItemFactory::getInstance()->get($id, $meta, $count);
        } elseif (in_array(self::getTypeOfQuest(), [DailyQuestType::BREAK, DailyQuestType::PLACE])) return BlockFactory::getInstance()->get($id, $meta);
        return null;
    }

    public static function getAllQuests(): array
    {
        return self::$quests;
    }

    public static function getAllPlayerFinish(): array
    {
        return self::$questData->get("players");
    }

    /**
     * @throws JsonException
     */
    public static function save(): void
    {
        self::$questData->save();
    }
}