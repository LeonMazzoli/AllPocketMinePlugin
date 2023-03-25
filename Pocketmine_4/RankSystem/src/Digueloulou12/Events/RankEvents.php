<?php

namespace Digueloulou12\Events;

use pocketmine\event\player\PlayerChatEvent;
use pocketmine\event\player\PlayerJoinEvent;
use Ayzrix\SimpleFaction\API\FactionsAPI;
use pocketmine\event\Listener;
use pocketmine\player\Player;
use Digueloulou12\Rank;
use pocketmine\Server;

class RankEvents implements Listener
{
    public function onJoin(PlayerJoinEvent $event)
    {
        Rank::getInstance()->createPlayer($event->getPlayer());
        Rank::getInstance()->initPermission($event->getPlayer());

        self::updateNameTag($event->getPlayer());
    }

    public function onChat(PlayerChatEvent $event)
    {
        $name = $event->getPlayer()->getName();
        $rank = Rank::getInstance()->getRank($event->getPlayer());

        $color = Rank::getInstance()->getRankColor($rank);

        $fac = self::getFaction($event->getPlayer());
        $fac_rank = self::getRankFaction($event->getPlayer());

        $event->setFormat(Rank::getInstance()->getConfigReplace("chat_format", ["{rank}", "{player}", "{msg}", "{color}", "{faction}", "{fac_rank}"], [$rank, $name, $event->getMessage(), $color, $fac, $fac_rank]));
    }

    public static function getFaction(Player $player): string
    {
        if (Rank::$faction === "SimpleFaction") {
            if (FactionsAPI::isInFaction($player->getName())) {
                return FactionsAPI::getFaction($player->getName());
            }
        } elseif (Rank::$faction === "PiggyFactions") {
            $member = Server::getInstance()->getPluginManager()->getPlugin("PiggyFactions")->getPlayerManager()->getPlayer($player);
            $faction = $member?->getFaction();
            if ($faction === null) return "";
            return $faction->getName();
        }
        return "";
    }

    public static function getRankFaction(Player $player): string
    {
        if (Rank::$faction === "SimpleFaction") {
            if (FactionsAPI::isInFaction($player->getName())) {
                if (FactionsAPI::getRank($player->getName()) === "Leader") {
                    return "**";
                } elseif (FactionsAPI::getRank($player->getName()) === "Officer") {
                    return "*";
                }
            }
        } elseif (Rank::$faction === "PiggyFactions") {
            $member = Server::getInstance()->getPluginManager()->getPlugin("PiggyFactions")->getPlayerManager()->getPlayer($player);
            $symbol = $member === null ? null : Server::getInstance()->getPluginManager()->getPlugin("PiggyFactions")->getTagManager()->getPlayerRankSymbol($member);
            if ($member === null || $symbol === null) return "";
            return $symbol;
        }
        return "";
    }

    public static function updateNameTag(Player $player): void
    {
        $name = $player->getName();
        $rank = Rank::getInstance()->getRank($player);

        $color = Rank::getInstance()->getRankColor($rank);

        $fac = self::getFaction($player);
        $fac_rank = self::getRankFaction($player);

        $player->setNameTag(Rank::getInstance()->getConfigReplace("nametag", ["{rank}", "{player}", "{color}", "{faction}", "{fac_rank}"], [$rank, $name, $color, $fac, $fac_rank]));
    }
}