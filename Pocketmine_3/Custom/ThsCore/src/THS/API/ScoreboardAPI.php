<?php

namespace THS\API;

use pocketmine\network\mcpe\protocol\RemoveObjectivePacket;
use pocketmine\network\mcpe\protocol\SetDisplayObjectivePacket;
use pocketmine\network\mcpe\protocol\SetScorePacket;
use pocketmine\network\mcpe\protocol\types\ScorePacketEntry;
use pocketmine\Player;
use pocketmine\Server;
use HiroTeam\KDR\KDRMain;

class ScoreboardAPI{
    private $scoreboard = [];
    private $main = [];
    private $duel = [];

    public function sendMainScoreboard(Player $player): void
    {
        if ($this->isScoreboardEnabled($player) === false) {
            return;
        }

        if ($this->isPlayerSetScoreboard($player)) {
            $this->removeScoreboard($player);
        }

        $rank = Server::getInstance()->getPluginManager()->getPlugin("PurePerms")->getUserDataMgr()->getGroup($player);

        $this->lineTitle($player, "§f----- §aThs §f-----");
        $this->lineCreate($player, 1, "§aPlayer:");
        $this->lineCreate($player, 2, " §fPseudo: §a{$player->getName()}");
        $this->lineCreate($player, 3, " §fGrade: §a$rank");
        $this->lineCreate($player, 4, " §fMoney: §a". MoneyAPI::myMoney($player));
        $this->lineCreate($player, 5, " §fK: §a".KDRMain::getInstance()->getProvider()->getOne($player->getName(),'kill')." §fD: §a".KDRMain::getInstance()->getProvider()->getOne($player->getName(),'death')." §fR: §a".KDRMain::getInstance()->getProvider()->getOneRatio($player->getName()));
        $this->lineCreate($player, 6, "  ");
        $this->lineCreate($player, 7, "§aServeur:");
        $this->lineCreate($player, 8, " §fEn ligne: §a".count(Server::getInstance()->getOnlinePlayers())."§f/§a".Server::getInstance()->getMaxPlayers());
        $this->lineCreate($player, 9, " §fTps: §a".Server::getInstance()->getTicksPerSecond());
        $this->lineCreate($player, 10, " §fTon Ping: §a". $player->getPing());
        $this->lineCreate($player, 11, " §fVoteParty: §asoon...");
        $this->lineCreate($player, 12, "§f--- §aThs-mc.fr §f---");
        $this->scoreboard[$player->getName()] = $player->getName();
        $this->main[$player->getName()] = $player->getName();
    }

    public function updateScoreboard($player)
    {
        if ($this->isScoreboardEnabled($player) === false) return;

        if ($this->isPlayerSetScoreboard($player)) {
            if ($this->isPlayerSetMain($player)) {
                $rank = Server::getInstance()->getPluginManager()->getPlugin("PurePerms")->getUserDataMgr()->getGroup($player);

                $this->lineRemove($player, 2);
                $this->lineRemove($player, 3);
                $this->lineRemove($player, 4);
                $this->lineRemove($player, 5);
                $this->lineRemove($player, 8);
                $this->lineRemove($player, 9);
                $this->lineRemove($player, 10);
                $this->lineRemove($player, 11);

                $this->lineCreate($player, 2, " §fPseudo: §a{$player->getName()}");
                $this->lineCreate($player, 3, " §fGrade: §a$rank");
                $this->lineCreate($player, 4, " §fMoney: §a". MoneyAPI::myMoney($player));
                $this->lineCreate($player, 5, " §fK: §a".KDRMain::getInstance()->getProvider()->getOne($player->getName(),'kill')." §fD: §a".KDRMain::getInstance()->getProvider()->getOne($player->getName(),'death')." §fR: §a".KDRMain::getInstance()->getProvider()->getOneRatio($player->getName()));
                $this->lineCreate($player, 8, " §fEn ligne: §a".count(Server::getInstance()->getOnlinePlayers())."§f/§a".Server::getInstance()->getMaxPlayers());
                $this->lineCreate($player, 9, " §fTps: §a".Server::getInstance()->getTicksPerSecond());
                $this->lineCreate($player, 10, " §fTon Ping: §a". $player->getPing());
                $this->lineCreate($player, 11, " §fVoteParty: §asoon...");
            }
        }
    }

    public function isPlayerSetScoreboard($player): bool
    {
        $name = $player->getName();
        return ($name !== null) and isset($this->scoreboard[$name]);
    }

    public function isPlayerSetMain($player): bool
    {
        $name = $player->getName();
        return ($name !== null) and isset($this->main[$name]);
    }

    public function lineTitle($player, string $title)
    {
        if ($this->isScoreboardEnabled($player) === false) {
            return;
        }

        $packet = new SetDisplayObjectivePacket();
        $packet->displaySlot = "sidebar";
        $packet->objectiveName = "objective";
        $packet->displayName = $title;
        $packet->criteriaName = "dummy";
        $packet->sortOrder = 0;
        $player->sendDataPacket($packet);
    }

    public function removeScoreboard($player)
    {
        $packet = new RemoveObjectivePacket();
        $packet->objectiveName = "objective";
        $player->sendDataPacket($packet);
        unset($this->scoreboard[$player->getName()]);
        unset($this->main[$player->getName()]);
        unset($this->duel[$player->getName()]);
    }

    public function lineCreate($player, int $line, string $content)
    {
        if ($this->isScoreboardEnabled($player) === false) {
            return;
        }

        $packetline = new ScorePacketEntry();
        $packetline->objectiveName = "objective";
        $packetline->type = ScorePacketEntry::TYPE_FAKE_PLAYER;
        $packetline->customName = " " . $content . "   ";
        $packetline->score = $line;
        $packetline->scoreboardId = $line;
        $packet = new SetScorePacket();
        $packet->type = SetScorePacket::TYPE_CHANGE;
        $packet->entries[] = $packetline;
        $player->sendDataPacket($packet);
    }

    public function lineRemove($player, int $line)
    {
        if ($this->isScoreboardEnabled($player) === false) {
            return;
        }

        $entry = new ScorePacketEntry();
        $entry->objectiveName = "objective";
        $entry->score = $line;
        $entry->scoreboardId = $line;
        $packet = new SetScorePacket();
        $packet->type = SetScorePacket::TYPE_REMOVE;
        $packet->entries[] = $entry;
        $player->sendDataPacket($packet);
    }

    public function isScoreboardEnabled(Player $player)
    {
        return PlayersAPI::getInfo($player, "scoreboard");
    }
}