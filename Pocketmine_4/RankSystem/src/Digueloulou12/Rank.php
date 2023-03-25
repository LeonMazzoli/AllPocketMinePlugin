<?php

namespace Digueloulou12;

use Digueloulou12\Commands\{AddPermission, AddRank, ListRanks, RemovePermission, RemoveRank, SetRank};
use Digueloulou12\Events\RankEvents;
use pocketmine\plugin\PluginBase;
use pocketmine\player\Player;
use pocketmine\utils\Config;

class Rank extends PluginBase
{
    public static Config $rank_data;
    public static string $faction;
    public static Config $data;
    private static Rank $rank;

    public function onEnable(): void
    {
        self::$rank = $this;
        $this->saveDefaultConfig();
        self::$data = new Config($this->getDataFolder() . "RankPlayer.json", Config::JSON);
        self::$rank_data = new Config($this->getDataFolder() . "Ranks.json", Config::JSON);

        if (!$this->existRank($this->getConfigValue("default_rank"))) {
            $this->addRank($this->getConfigValue("default_rank"));
        }

        $faction = $this->getConfigValue("faction_plugin");
        if (!is_null($faction)) {
            if ($this->getServer()->getPluginManager()->getPlugin($faction) === null) {
                $this->getLogger()->alert("PLUGIN $faction NOT FOUND");
                self::$faction = "";
            } else self::$faction = $faction;
        } else self::$faction = "";

        $this->getServer()->getPluginManager()->registerEvents(new RankEvents(), $this);
        $this->getServer()->getCommandMap()->registerAll("RankSystemCommand",
            [
                new AddPermission(),
                new AddRank(),
                new ListRanks(),
                new RemovePermission(),
                new RemoveRank(),
                new SetRank()
            ]);
    }

    public static function getInstance(): Rank
    {
        return self::$rank;
    }

    public function initPermission(Player $player): void
    {
        foreach ($this->getPermission($player, true) as $perm) {
            $player->addAttachment($this, $perm, true);
        }

        foreach ($this->getPermission($this->getRank($player), false) as $perm) {
            $player->addAttachment($this, $perm, true);
        }
    }

    public function getConfigValue(string $path, bool $nested = false): mixed
    {
        if ($nested) {
            return $this->getConfig()->getNested($path);
        } else return $this->getConfig()->get($path);
    }

    public function getConfigReplace(string $path, array|string $replace = [], array|string $replace_ = []): string
    {
        $return = str_replace("{prefix}", $this->getConfigValue("prefix"), $this->getConfigValue($path));
        return str_replace($replace, $replace_, $return);
    }

    public function getPlayerName($player): string
    {
        if ($player instanceof Player) return $player->getName(); else return $player;
    }

    public function existPlayer($player): bool
    {
        return self::$data->exists($this->getPlayerName($player));
    }

    public function existRank(string $rank): bool
    {
        return self::$rank_data->exists($rank);
    }

    public function createPlayer($player): void
    {
        if (!$this->existPlayer($player)) {
            self::$data->set($this->getPlayerName($player), ["rank" => $this->getConfigValue("default_rank"), "permissions" => []]);
            self::$data->save();
            return;
        }

        if (!$this->existRank($this->getRank($player))) {
            $this->setRank($player, $this->getConfigValue("default_rank"));
        }
    }

    public function getAllRanks(): array
    {
        $ranks = [];
        foreach (self::$rank_data->getAll() as $rank => $perms) {
            $ranks[] = $rank;
        }
        return $ranks;
    }

    public function addRank(string $rank): void
    {
        self::$rank_data->set($rank, []);
        self::$rank_data->save();
    }

    public function removeRank(string $rank): void
    {
        foreach (self::$data->getAll() as $player => $key) {
            if ($key["rank"] === $rank) {
                $this->setRank($player, $this->getConfigValue("default_rank"));
                $user = $this->getServer()->getPlayerByPrefix($player);
                if ($user instanceof Player) RankEvents::updateNameTag($user);
            }
        }

        self::$rank_data->remove($rank);
        self::$rank_data->save();
    }

    public function getRank($player): string
    {
        return self::$data->get($this->getPlayerName($player))["rank"];
    }

    public function getRankColor(string $rank): string
    {
        if ($this->getConfigValue("color.$rank", true) !== null) {
            return $this->getConfigValue("color")[$rank];
        } else return $this->getConfigValue("color")["default"];
    }

    public function setRank($player, string $rank): void
    {
        self::$data->setNested($this->getPlayerName($player) . ".rank", $rank);
        self::$data->save();

        if (!($player instanceof Player)) {
            $p = $this->getServer()->getPlayerByPrefix($player);
            if ($p instanceof Player) $this->initPermission($p);
        } else $this->initPermission($player);
    }

    public function getPermission($user, bool $player): array
    {
        if ($player) {
            return self::$data->get($this->getPlayerName($user))["permissions"];
        } else return self::$rank_data->get($user);
    }

    public function addPermission($user, string $perm, bool $player): void
    {
        if ($player) {
            $perms = $this->getPermission($user, true);
            $perms[] = $perm;
            self::$data->setNested($this->getPlayerName($user) . ".permissions", $perms);
            self::$data->save();

            if ($user instanceof Player) {
                $user->addAttachment($this, $perm, true);
            } else {
                $p = $this->getServer()->getInstance()->getPlayerByPrefix($user);
                if ($p instanceof Player) $p->addAttachment($this, $perm, true);
            }
        } else {
            $perms = $this->getPermission($user, false);
            $perms[] = $perm;
            self::$rank_data->set($user, $perms);
            self::$rank_data->save();

            foreach ($this->getServer()->getOnlinePlayers() as $onlinePlayer) {
                if ($this->getRank($onlinePlayer) === $user) {
                    $onlinePlayer->addAttachment($this, $perm, true);
                }
            }
        }
    }

    public function removePermission($user, string $perm, bool $player): void
    {
        if ($player) {
            $perms = $this->getPermission($user, true);
            unset($perms[array_search($perm, $perms)]);
            self::$data->setNested($this->getPlayerName($user) . ".permissions", $perms);
            self::$data->save();
        } else {
            $perms = $this->getPermission($user, false);
            unset($perms[array_search($perm, $perms)]);
            self::$rank_data->set($user, $perms);
            self::$rank_data->save();
        }
    }
}