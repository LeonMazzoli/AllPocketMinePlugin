<?php

namespace Digueloulou12\LobbyCore\API;

use Digueloulou12\LobbyCore\Tasks\UpdateTask;
use Digueloulou12\LobbyCore\Utils\Utils;
use JetBrains\PhpStorm\Pure;
use pocketmine\network\mcpe\protocol\RemoveObjectivePacket;
use pocketmine\network\mcpe\protocol\SetDisplayObjectivePacket;
use pocketmine\network\mcpe\protocol\SetScorePacket;
use pocketmine\network\mcpe\protocol\types\ScorePacketEntry;
use pocketmine\player\Player;
use pocketmine\Server;

class ScoreboardAPI
{
    private static int $lines = 0;
    private static array $scoreboard = [];

    public static function sendScoreboard(Player $player): void
    {
        if (self::isScoreboardEnable($player)) self::removeScoreboard($player);


        $online = UpdateTask::$connect;
        $maxOnline = UpdateTask::$maxConnect;
        $rank = RankAPI::getRank($player);

        self::$scoreboard[] = $player->getName();
        self::lineTitle($player, Utils::getConfigReplace("scoreboard_title"));
        $l = 1;
        foreach (Utils::getConfigValue("scoreboard") as $line) {
            self::lineCreate($player, $l, str_replace(["{online}", "{max_online}", "{rank}"], [$online, $maxOnline, $rank], $line));
            $l++;
        }
        self::$lines = $l;
    }

    public static function updateScoreboard(): void
    {
        foreach (Server::getInstance()->getOnlinePlayers() as $player) {
            for ($l = self::$lines; $l !== 0; $l--) {
                self::lineRemove($player, $l);
            }
            self::sendScoreboard($player);
        }
    }

    #[Pure] public static function isScoreboardEnable(Player $player): bool
    {
        return in_array($player->getName(), self::$scoreboard);
    }

    public static function removeScoreboard(Player $player): void
    {
        if (self::isScoreboardEnable($player) === false) return;

        $packet = new RemoveObjectivePacket();
        $packet->objectiveName = "objective";
        $player->getNetworkSession()->sendDataPacket($packet);
        unset(self::$scoreboard[$player->getName()]);
    }

    public static function lineTitle(Player $player, string $title): void
    {
        if (self::isScoreboardEnable($player) === false) return;

        $packet = new SetDisplayObjectivePacket();
        $packet->displaySlot = "sidebar";
        $packet->objectiveName = "objective";
        $packet->displayName = $title;
        $packet->criteriaName = "dummy";
        $packet->sortOrder = 0;
        $player->getNetworkSession()->sendDataPacket($packet);
    }

    public static function lineCreate(Player $player, int $line, string $content): void
    {
        if (self::isScoreboardEnable($player) === false) return;

        $packetline = new ScorePacketEntry();
        $packetline->objectiveName = "objective";
        $packetline->type = ScorePacketEntry::TYPE_FAKE_PLAYER;
        $packetline->customName = " " . $content . "   ";
        $packetline->score = $line;
        $packetline->scoreboardId = $line;
        $packet = new SetScorePacket();
        $packet->type = SetScorePacket::TYPE_CHANGE;
        $packet->entries[] = $packetline;
        $player->getNetworkSession()->sendDataPacket($packet);
    }

    public static function lineRemove(Player $player, int $line): void
    {
        if (self::isScoreboardEnable($player) === false) return;

        $entry = new ScorePacketEntry();
        $entry->objectiveName = "objective";
        $entry->score = $line;
        $entry->scoreboardId = $line;
        $packet = new SetScorePacket();
        $packet->type = SetScorePacket::TYPE_REMOVE;
        $packet->entries[] = $entry;
        $player->getNetworkSession()->sendDataPacket($packet);
    }
}