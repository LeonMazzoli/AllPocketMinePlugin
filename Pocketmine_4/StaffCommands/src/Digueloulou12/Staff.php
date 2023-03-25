<?php

namespace Digueloulou12;

use Digueloulou12\Commands\{Ban, BanList, Freeze, Kick, Mute, MuteList, Unban, Unfreeze, Unmute};
use CortexPE\DiscordWebhookAPI\Message;
use CortexPE\DiscordWebhookAPI\Webhook;
use Digueloulou12\Events\StaffEvents;
use pocketmine\command\CommandSender;
use pocketmine\plugin\PluginBase;
use pocketmine\player\Player;
use pocketmine\utils\Config;

class Staff extends PluginBase
{
    private static Staff $staff;
    public static Config $ban;
    private bool $discord;

    public function onEnable(): void
    {
        self::$staff = $this;
        $this->saveDefaultConfig();

        self::$ban = new Config($this->getDataFolder() . "BanData.json", Config::JSON);

        foreach ($this->getConfigValue("disable_commands") as $cmd) {
            $this->getServer()->getCommandMap()->unregister($this->getServer()->getCommandMap()->getCommand($cmd));
            $this->getLogger()->info("The Command $cmd as been disable !");
        }

        if ($this->getConfigValue("discord_integration") === true) {
            if ($this->getConfigValue("webhook") !== "") {
                $this->discord = true;
            } else $this->discord = false;
        } else $this->discord = false;

        $this->getServer()->getCommandMap()->registerAll("StaffCommands", [
            new Freeze(), new Unfreeze(), new Kick(), new Mute(), new Unmute(), new MuteList(), new Ban(), new Unban(), new BanList()
        ]);

        $this->getServer()->getPluginManager()->registerEvents(new StaffEvents(), $this);
    }

    public function sendDiscordMessage($player, $moderator, ?string $reason, string $path, ?string $time = null): void
    {
        if ($this->discord) {
            $webhook = new Webhook($this->getConfigValue("webhook"));
            if ($webhook->isValid()) {
                $message = new Message();
                $message->setContent($this->getConfigReplace($path, ["{player}", "{moderator}", "{time}", "{reason}"], [$this->getPlayerName($player), $this->getPlayerName($moderator), $time, $reason]));
                $webhook->send($message);
            }
        }
    }

    public function hasPermissionPlayer(CommandSender $sender, string $command, bool $perm = false): bool
    {
        if ($this->getServer()->isOp($sender->getName())) return true;
        if ($sender instanceof Player) {
            if ($perm) {
                if (!$sender->hasPermission($command)) {
                    $sender->sendMessage($this->getConfigReplace("no_perm", "{perm}", $command));
                    return false;
                } else return true;
            } else {
                if (isset($this->getConfigValue($command)[2])) {
                    if (!$sender->hasPermission($this->getConfigValue($command)[2])) {
                        $sender->sendMessage($this->getConfigReplace("no_perm", "{perm}", $this->getConfigValue($command)[2]));
                        return false;
                    } else return true;
                } else return true;
            }
        } else return true;
    }

    public function getConfigValue(string $path, $nested = false): mixed
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

    public function getTime($time): ?int
    {
        return match (substr($time, -1)) {
            "d" => (int)$time * 24 * 60 * 60,
            "h" => (int)$time * 60 * 60,
            "m" => (int)$time * 60,
            "s" => (int)$time,
            default => null
        };
    }

    public function getRemainingTime(int $time, string $type): int
    {
        $remainingTime = $time - time();
        $day = floor($remainingTime / 86400);
        $hourSeconds = $remainingTime % 86400;
        $hour = floor($hourSeconds / 3600);
        $minuteSec = $hourSeconds % 3600;
        $minute = floor($minuteSec / 60);
        $remainingSec = $minuteSec % 60;
        $second = ceil($remainingSec);

        return match ($type) {
            "day" => $day,
            "hour" => $hour,
            "minute" => $minute,
            "second" => $second
        };
    }

    public function isBannedPlayer($player): bool
    {
        if (self::$ban->exists($this->getPlayerName($player))) {
            if (self::$ban->get($this->getPlayerName($player))[1] < time()) {
                self::removeBan($player);
                return false;
            } else return true;
        } else return false;
    }

    public function addBan($player, $staff, string $reason, int $time): void
    {
        $user = $this->getServer()->getPlayerByPrefix($this->getPlayerName($player));
        if ($user instanceof Player) $user->kick($this->getConfigReplace("ban_kick", ["{player}", "{reason}"], [$this->getPlayerName($staff), $reason]));

        self::$ban->set($this->getPlayerName($player), [$reason, time() + $time, $this->getPlayerName($staff)]);
        self::$ban->save();
    }

    public function getAllBan(): array
    {
        return self::$ban->getAll();
    }

    public function removeBan($player): void
    {
        self::$ban->remove($this->getPlayerName($player));
        self::$ban->save();
    }

    public function getReasonBan($player): string
    {
        return self::$ban->get($this->getPlayerName($player))[0];
    }

    public function getTimeBan($player): int
    {
        return self::$ban->get($this->getPlayerName($player))[1];
    }

    public function getStaffBan($player): string
    {
        return self::$ban->get($this->getPlayerName($player))[2];
    }

    public function getPlayerName($player): string
    {
        if ($player instanceof Player) return $player->getName(); elseif ($player instanceof CommandSender) return 'Server';
        else return $player;
    }

    public static function getInstance(): Staff
    {
        return self::$staff;
    }
}