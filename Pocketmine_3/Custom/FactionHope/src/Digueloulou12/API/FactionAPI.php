<?php

namespace Digueloulou12\API;

use Digueloulou12\Command\FactionCommand;
use pocketmine\level\format\Chunk;
use pocketmine\level\Position;
use pocketmine\utils\Config;
use Digueloulou12\MainF;
use pocketmine\Player;
use pocketmine\Server;

class FactionAPI
{
    public static array $invitation_ally = [];
    public static array $invitation = [];
    public static array $ally_chat = [];
    public static array $admin = [];
    public static array $chat = [];
    public static array $fly = [];
    public static Config $config;
    public static Config $claim;

    public function __construct()
    {
        self::$config = new Config(MainF::getInstance()->getDataFolder() . "faction.json", Config::JSON);
        self::$claim = new Config(MainF::getInstance()->getDataFolder() . "claim.json", Config::JSON);

        MainF::getInstance()->getLogger()->info("Faction on by Digueloulou12");
        Server::getInstance()->getCommandMap()->register("", new FactionCommand());
    }

    public static function isInFaction($player): bool
    {
        $return = false;
        foreach (self::$config->getAll() as $fac_name => $key) {
            foreach (self::$config->get("$fac_name")["Members"] as $member_name => $rank) {
                if ($member_name === MainF::getPlayerName($player)) $return = true;
            }
        }

        return $return;
    }

    public static function existFaction(string $name): bool
    {
        $return = false;
        foreach (self::$config->getAll() as $fac_name => $key) {
            if ($fac_name === $name) $return = true;
        }

        return $return;
    }

    public static function createFaction($player, string $fac_name)
    {
        self::$config->set($fac_name, ["Members" => [MainF::getPlayerName($player) => "Owner"], "desc" => " ", "power" => ConfigAPI::getConfigValue("default_power"), "home" => " ", "ally" => []]);
        self::$config->save();
    }

    public static function getOwnerPlayer(string $fac_name): string
    {
        $return = "";
        foreach (self::$config->get($fac_name)["Members"] as $name => $rank) {
            if ($rank === "Owner") $return = $name;
        }

        return $return;
    }

    public static function getFactionPlayer($player): string
    {
        $return = "";
        foreach (self::$config->getAll() as $fac_name => $key) {
            foreach ($key["Members"] as $name => $rank) {
                if ($name === MainF::getPlayerName($player)) $return = $fac_name;
            }
        }

        return $return;
    }

    public static function disbandFaction(string $fac_name)
    {
        self::$config->remove($fac_name);
        self::$config->save();
    }

    public static function addMemberInFaction($player, string $fac_name)
    {
        self::$config->setNested("$fac_name.Members." . MainF::getPlayerName($player), "Membre");
        self::$config->save();
    }

    public static function getRankPlayerChatInFaction($player, string $fac_name): string
    {
        $rank = self::$config->get($fac_name)["Members"][MainF::getPlayerName($player)];

        $return = "";
        if ($rank === "Owner") $return = "**";
        if ($rank === "Sous-chef") $return = "*";
        if ($rank === "Officier") $return = "-";

        return $return;
    }

    public static function sendMessageFaction(string $msg, string $fac_name, $player_)
    {
        foreach (self::$config->get($fac_name)["Members"] as $player => $rank) {
            $sender = Server::getInstance()->getPlayer($player);
            if ($sender instanceof Player) $sender->sendMessage(ConfigAPI::getConfigReplace("chat", ["{rank}", "{player}", "{faction}", "{msg}"], [self::getRankPlayerChatInFaction($sender, self::getFactionPlayer($sender)), MainF::getPlayerName($player_), $fac_name, $msg]));
        }
    }

    public static function sendAllyMsg(string $fac_name, string $msg, Player $player_)
    {
        foreach (self::getAllyFaction($fac_name) as $ally) {
            foreach (self::getMembersFaction($ally) as $sender) {
                $player = Server::getInstance()->getPlayer($sender);
                if ($player instanceof Player) $player->sendMessage(ConfigAPI::getConfigReplace("ally_chat", ["{rank}", "{player}", "{faction}", "{msg}"], [self::getRankPlayerChatInFaction($player_, self::getFactionPlayer($player_)), MainF::getPlayerName($player_), $fac_name, $msg]));
            }
        }
        $player_->sendMessage(ConfigAPI::getConfigReplace("ally_chat", ["{rank}", "{player}", "{faction}", "{msg}"], [self::getRankPlayerChatInFaction($player_, self::getFactionPlayer($player_)), MainF::getPlayerName($player_), $fac_name, $msg]));
    }

    public static function setDescriptionFaction(string $desc, string $fac_name)
    {
        self::$config->setNested("$fac_name.desc", $desc);
        self::$config->save();
    }

    public static function removePlayerInFaction($player, string $fac_name)
    {
        self::removePowerFaction($fac_name, self::$config->get(MainF::getPlayerName($player)));
        self::$config->removeNested("$fac_name.Members." . MainF::getPlayerName($player));
        self::$config->save();
    }

    public static function getPowerFaction(string $fac_name): int
    {
        return self::$config->get($fac_name)["power"];
    }

    public static function addPowerFaction(string $fac_name, int $power)
    {
        self::$config->setNested("$fac_name.power", self::$config->get($fac_name)["power"] + $power);
        self::$config->save();
    }

    public static function removePowerFaction(string $fac_name, int $power)
    {
        self::$config->setNested("$fac_name.power", self::$config->get($fac_name)["power"] - $power);
        self::$config->save();
    }

    public static function getMembersFaction(string $fac_name): array
    {
        $array = [];
        foreach (self::$config->get($fac_name)["Members"] as $member => $rank) {
            $array[] = $member;
        }
        return $array;
    }

    public static function getMessageInfo(string $fac_name): string
    {
        $mem = self::getMembersFaction($fac_name);

        $members = [];
        $owner = [];
        $off = [];
        foreach ($mem as $name) {
            if (self::getRankPlayerChatInFaction($name, $fac_name) === "") $members[] = $name;
            if (self::getRankPlayerChatInFaction($name, $fac_name) === "*") $owner[] = $name;
            if (self::getRankPlayerChatInFaction($name, $fac_name) === "-") $off[] = $name;
        }

        return ConfigAPI::getConfigReplace("msg_info",
            ["{power}", "{name}", "{owner}", "{members}", "{desc}", "{player}", "{max_player}", "{owner-}", "{officier}"],
            [self::getPowerFaction($fac_name), $fac_name, self::getOwnerPlayer($fac_name), implode(", ", $members), self::$config->getNested("$fac_name.desc"), self::countPlayerInFaction($fac_name), ConfigAPI::getConfigValue("max_player_per_faction"), implode(", ", $owner), implode(", ", $off)]);
    }

    public static function setOwnerFaction($player, string $fac_name)
    {
        $owner = self::getOwnerPlayer($fac_name);
        self::$config->setNested("$fac_name.Members.$owner", "Officier");
        self::$config->setNested("$fac_name.Members." . MainF::getPlayerName($player), "Owner");
        self::$config->save();
    }

    public static function hasHome(string $fac_name): bool
    {
        if (self::$config->get($fac_name)["home"] === " ") return false; else return true;
    }

    public static function setHome(Player $player, string $fac_name)
    {
        self::$config->setNested("$fac_name.home", [$player->getFloorX(), $player->getFloorY(), $player->getFloorZ(), $player->getLevel()->getName()]);
        self::$config->save();
    }

    public static function getPostitionHome(string $fac_name): Position
    {
        $pos = self::$config->get($fac_name)["home"];
        return new Position((int)$pos[0], (int)$pos[1], (int)$pos[2], Server::getInstance()->getLevelByName($pos[3]));
    }

    public static function promotePlayerInFaction($player, string $fac_name)
    {
        if (self::getRankPlayerChatInFaction($player, $fac_name) === "") {
            self::$config->setNested("$fac_name.Members." . MainF::getPlayerName($player), "-");
        } elseif (self::getRankPlayerChatInFaction($player, $fac_name) === "-") self::$config->setNested("$fac_name.Members." . MainF::getPlayerName($player), "*");

        self::$config->save();
    }

    public static function sendTopFaction(Player $player)
    {
        $player->sendMessage(ConfigAPI::getConfigReplace("title_top"));

        $power = [];
        foreach (self::$config->getAll() as $fac_name => $key) {
            $power[$fac_name] = $key["power"];
        }

        $i = 1;
        arsort($power);
        foreach ($power as $fac => $value) {
            if ($i !== ConfigAPI::getConfigValue("top_faction") + 1) {
                $player->sendMessage(ConfigAPI::getConfigReplace("info_top", ["{top}", "{faction}", "{power}"], [$i, $fac, $value]));
                $i++;
            } else break;
        }
    }

    public static function countPlayerInFaction(string $fac_name): int
    {
        $count = 0;
        foreach (self::$config->get($fac_name)["Members"] as $member => $rank) {
            $count++;
        }
        return $count;
    }

    public static function isChunkClaim(Chunk $chunk): bool
    {
        $return = false;
        if (self::$claim->exists("{$chunk->getX()}:{$chunk->getZ()}")) $return = true;

        return $return;
    }

    public static function countClaim(string $fac_name): int
    {
        $claim = 0;
        foreach (self::$claim->getAll() as $claim_ => $faction) {
            if ($faction === $fac_name) $claim++;
        }

        return $claim;
    }

    public static function claimChunk(Chunk $chunk, string $fac_name)
    {
        self::removePowerFaction($fac_name, ConfigAPI::getConfigValue("power_per_claim"));
        self::$claim->set("{$chunk->getX()}:{$chunk->getZ()}", $fac_name);
        self::$claim->save();
    }

    public static function getFactionClaim(Chunk $chunk): string
    {
        return self::$claim->get("{$chunk->getX()}:{$chunk->getZ()}");
    }

    public static function unclaimChunk(Chunk $chunk)
    {
        $pos = "{$chunk->getX()}:{$chunk->getZ()}";
        self::addPowerFaction(self::$claim->get($pos), ConfigAPI::getConfigValue("power_per_claim"));
        self::$claim->remove($pos);
        self::$claim->save();
    }

    public static function getAllyFaction(string $fac_name): array
    {
        $ally_ = [];
        foreach (self::$config->get($fac_name)["ally"] as $ally) {
            $ally_[] = $ally;
        }

        return $ally_;
    }

    public static function addAlly(string $fac_one, string $fac_two)
    {
        $ally = self::$config->get($fac_one)["ally"];
        array_push($ally, $fac_two);
        self::$config->setNested("$fac_one.ally", $ally);

        $ally_ = self::$config->get($fac_two)["ally"];
        array_push($ally_, $fac_one);
        self::$config->setNested("$fac_two.ally", $ally_);
        self::$config->save();
    }

    public static function removeAlly(string $fac_name, string $fac_ally)
    {
        $ally = self::$config->get($fac_name)["ally"];
        unset($ally[$fac_ally]);
        self::$config->setNested("$fac_name.ally", $ally);

        $ally_ = self::$config->get($fac_ally)["ally"];
        unset($ally_[$fac_name]);
        self::$config->setNested("$fac_ally.ally", $ally_);
        self::$config->save();
    }
}