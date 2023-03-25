<?php

namespace Digueloulou12;

use pocketmine\Player;
use Digueloulou12\Task\FlyTask;
use pocketmine\plugin\PluginBase;
use Digueloulou12\API\FactionAPI;
use Digueloulou12\Events\ChatEvent;
use Digueloulou12\Events\ClaimEvents;
use Digueloulou12\Events\PlayerEvents;

class MainF extends PluginBase
{
    private static MainF $main;

    public function onEnable()
    {
        self::$main = $this;
        $this->saveResource("config.yml");
        new FactionAPI();
        $this->getScheduler()->scheduleRepeatingTask(new FlyTask(), 20 * 2);
        $this->getServer()->getPluginManager()->registerEvents(new ChatEvent(), $this);
        $this->getServer()->getPluginManager()->registerEvents(new ClaimEvents(), $this);
        $this->getServer()->getPluginManager()->registerEvents(new PlayerEvents(), $this);
    }

    public static function getPlayerName($player): string
    {
        if ($player instanceof Player) return $player->getName(); else return $player;
    }

    public static function getInstance(): MainF
    {
        return self::$main;
    }
}