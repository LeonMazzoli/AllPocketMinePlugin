<?php

namespace Digueloulou12\LobbyCore\API;

use Digueloulou12\LobbyCore\Commands\Ranks\{AddPermission, AddRank, ListRanks, RemovePermission, RemoveRank, SetRank};
use Digueloulou12\LobbyCore\LobbyCore;
use Digueloulou12\LobbyCore\Utils\Utils;
use JetBrains\PhpStorm\Pure;
use JsonException;
use pocketmine\player\Player;
use pocketmine\utils\Config;
use pocketmine\Server;

class RankAPI
{
    public static Config $rank_data;
    public static Config $data;

    /**
     * @throws JsonException
     */
    public function __construct()
    {
        self::$rank_data = new Config(LobbyCore::getInstance()->getDataFolder() . "Ranks.json", Config::JSON);
        self::$data = new Config(LobbyCore::getInstance()->getDataFolder() . "RankPlayer.json", Config::JSON);

        if (!self::existRank(Utils::getConfigValue("rank_default"))) {
            self::addRank(Utils::getConfigValue("rank_default"));
        }

        $commands = [new AddPermission(), new AddRank(), new ListRanks(), new RemovePermission(), new RemoveRank(), new SetRank()];
        Server::getInstance()->getCommandMap()->registerAll("LobbyCoreCommands", $commands);
    }

    public static function initPermission(Player $player): void
    {
        foreach (self::getPermission($player, true) as $perm) {
            $player->addAttachment(LobbyCore::getInstance(), $perm, true);
        }

        foreach (self::getPermission(self::getRank($player), false) as $perm) {
            $player->addAttachment(LobbyCore::getInstance(), $perm, true);
        }
    }

    public static function existPlayer($player): bool
    {
        return self::$data->exists(Utils::getPlayerName($player));
    }

    public static function existRank(string $rank): bool
    {
        return self::$rank_data->exists($rank);
    }

    /**
     * @throws JsonException
     */
    public static function createPlayer($player): void
    {
        if (!self::existPlayer($player)) {
            self::$data->set(Utils::getPlayerName($player), ["rank" => Utils::getConfigValue("rank_default"), "permissions" => []]);
            self::$data->save();
        }
    }

    public static function getRankColor(string $rank): string
    {
        if (isset(Utils::getConfigValue("rank_color")[$rank])) {
            return Utils::getConfigValue("rank_color")[$rank];
        } else return Utils::getConfigValue("rank_color")["default"];
    }

    #[Pure] public static function getAllRanks(): array
    {
        $ranks = [];
        foreach (self::$rank_data->getAll() as $rank => $perms) {
            $ranks[] = $rank;
        }
        return $ranks;
    }

    /**
     * @throws JsonException
     */
    public static function addRank(string $rank): void
    {
        self::$rank_data->set($rank, []);
        self::$rank_data->save();
    }

    /**
     * @throws JsonException
     */
    public static function removeRank(string $rank): void
    {
        self::$rank_data->remove($rank);
        self::$rank_data->save();
    }

    public static function getRank($player): string
    {
        return self::$data->get(Utils::getPlayerName($player))["rank"];
    }

    /**
     * @throws JsonException
     */
    public static function setRank($player, string $rank): void
    {
        self::$data->setNested(Utils::getPlayerName($player) . ".rank", $rank);
        self::$data->save();
    }

    public static function getPermission($user, bool $player): array
    {
        if ($player) {
            return self::$data->get(Utils::getPlayerName($user))["permissions"];
        } else return self::$rank_data->get($user);
    }

    /**
     * @throws JsonException
     */
    public static function addPermission($user, string $perm, bool $player): void
    {
        if ($player) {
            $perms = self::getPermission($user, true);
            $perms[] = $perm;
            self::$data->setNested(Utils::getPlayerName($user) . ".permissions", $perms);
            self::$data->save();

            if ($user instanceof Player) {
                $user->addAttachment(LobbyCore::getInstance(), $perm, true);
            } else {
                $p = Server::getInstance()->getPlayerByPrefix($user);
                if ($p instanceof Player) $p->addAttachment(LobbyCore::getInstance(), $perm, true);
            }
        } else {
            $perms = self::getPermission($user, false);
            $perms[] = $perm;
            self::$rank_data->set($user, $perms);
            self::$rank_data->save();

            foreach (Server::getInstance()->getOnlinePlayers() as $onlinePlayer) {
                if (self::getRank($onlinePlayer) === $user) {
                    $onlinePlayer->addAttachment(LobbyCore::getInstance(), $perm, true);
                }
            }
        }
    }

    /**
     * @throws JsonException
     */
    public static function removePermission($user, string $perm, bool $player): void
    {
        if ($player) {
            $perms = self::getPermission($user, true);
            unset($perms[array_search($perm, $perms)]);
            self::$data->setNested(Utils::getPlayerName($user) . ".permissions", $perms);
            self::$data->save();
        } else {
            $perms = self::getPermission($user, false);
            unset($perms[array_search($perm, $perms)]);
            self::$rank_data->set($user, $perms);
            self::$rank_data->save();
        }
    }

    public static function setNameTag(Player $player): void
    {
        $health = $player->getHealth() / $player->getMaxHealth();
        $health = $health * 100;

        $rank = self::getRank($player);
        $color = self::getRankColor($rank);

        $player->setNameTag(Utils::getConfigReplace("nametag", ["{rank}", "{health}", "{color}", "{name}"], [$rank, $health, $color, $player->getName()]));
    }
}