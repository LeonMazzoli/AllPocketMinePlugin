<?php

namespace Digueloulou12;

use Digueloulou12\Tasks\FlyTask;
use pocketmine\Player;
use pocketmine\plugin\PluginBase;
use Digueloulou12\API\FactionAPI;
use Digueloulou12\Tasks\ChunkTask;
use Digueloulou12\Events\ChatEvent;
use Digueloulou12\Events\ClaimEvents;
use Digueloulou12\Events\EntityEvents;

class Main extends PluginBase
{
    private static Main $main;

    public function onEnable()
    {
        $this->getServer()->getPluginManager()->registerEvents(new EntityEvents(), $this);
        $this->getServer()->getPluginManager()->registerEvents(new ClaimEvents(), $this);
        $this->getServer()->getPluginManager()->registerEvents(new ChatEvent(), $this);
        $this->getScheduler()->scheduleRepeatingTask(new FlyTask(), 20 * 2);
        $this->getScheduler()->scheduleRepeatingTask(new ChunkTask(), 15);
        $this->saveResource("config.yml");
        self::$main = $this;
        new FactionAPI();
    }

    public static function getPlayerName($player): string
    {
        if ($player instanceof Player) return $player->getName(); else return $player;
    }

    public static function getInstance(): Main
    {
        return self::$main;
    }
}