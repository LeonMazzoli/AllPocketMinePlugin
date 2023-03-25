<?php

namespace Digueloulou12;

use Digueloulou12\Commands\RewardTopCommand;
use Digueloulou12\Commands\RewardCommand;
use Digueloulou12\Events\RewardEvents;
use pocketmine\plugin\PluginBase;
use Digueloulou12\Task\DayTask;
use pocketmine\utils\Config;

class Reward extends PluginBase
{
    private static Reward $main;
    public static Config $data;

    public function onEnable(): void
    {
        self::$main = $this;
        $this->saveDefaultConfig();
        self::$data = new Config($this->getDataFolder() . "DayData.json", Config::JSON);

        $this->getServer()->getPluginManager()->registerEvents(new RewardEvents(), $this);

        $this->getServer()->getCommandMap()->registerAll("Reward", [
            new RewardCommand($this->getConfigValue("reward")[0], isset($this->getConfigValue("reward")[1]) ? $this->getConfigValue("reward")[1] : ""),
            new RewardTopCommand($this->getConfigValue("top")[0], isset($this->getConfigValue("top")[1]) ? $this->getConfigValue("top")[1] : "")
        ]);

        $this->getScheduler()->scheduleRepeatingTask(new DayTask(), 20 * 60);
    }

    public function getConfigValue(string $path, bool $nested = false): mixed
    {
        return $nested ? $this->getConfig()->getNested($path) : $this->getConfig()->get($path);
    }

    public static function getInstance(): Reward
    {
        return self::$main;
    }
}