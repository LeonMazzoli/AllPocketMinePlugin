<?php

namespace Digueloulou12;

use pocketmine\event\player\PlayerJoinEvent;
use Digueloulou12\Commands\TokenRemove;
use Digueloulou12\Commands\TokenMenu;
use Digueloulou12\Commands\TokenAdd;
use Digueloulou12\Commands\TokenSee;
use pocketmine\plugin\PluginBase;
use pocketmine\event\Listener;
use pocketmine\player\Player;
use pocketmine\utils\Config;

class Token extends PluginBase implements Listener
{
    private static Token $main;
    private static Config $data;

    public function onEnable(): void
    {
        self::$main = $this;
        $this->saveDefaultConfig();
        self::$data = new Config($this->getDataFolder() . "Token.json", Config::JSON);

        $this->getServer()->getPluginManager()->registerEvents($this, $this);

        $this->getServer()->getCommandMap()->register("", new TokenRemove());
        $this->getServer()->getCommandMap()->register("", new TokenMenu());
        $this->getServer()->getCommandMap()->register("", new TokenAdd());
        $this->getServer()->getCommandMap()->register("", new TokenSee());
    }

    public static function getMain(): Token
    {
        return self::$main;
    }

    public function onJoin(PlayerJoinEvent $event)
    {
        if (!$this->existToken($event->getPlayer())) {
            self::$data->set($event->getPlayer()->getName(), $this->getConfig()->get("default_token"));
            self::$data->save();
        }
    }

    public function getPlayerName($player): string
    {
        if ($player instanceof Player) return $player->getName(); else return $player;
    }

    public function existToken($player): bool
    {
        return self::$data->exists($this->getPlayerName($player));
    }

    public function addToken($player, int $token): void
    {
        $this->setToken($player, $this->getToken($player) + $token);
    }

    public function removeToken($player, int $token): void
    {
        $this->setToken($player, $this->getToken($player) - $token);
    }

    public function setToken($player, int $token): void
    {
        self::$data->set($this->getPlayerName($player), $token);
        self::$data->save();
    }

    public function getToken($player): int
    {
        return self::$data->get($this->getPlayerName($player));
    }
}