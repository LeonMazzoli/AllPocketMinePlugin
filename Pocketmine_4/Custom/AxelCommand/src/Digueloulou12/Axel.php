<?php

namespace Digueloulou12;

use Digueloulou12\Commands\Feed;
use Digueloulou12\Commands\Furnace;
use Digueloulou12\Commands\Heal;
use pocketmine\plugin\PluginBase;

class Axel extends PluginBase
{
    private static Axel $main;

    public function onEnable(): void
    {
        self::$main = $this;

        $this->saveDefaultConfig();

        $this->getServer()->getCommandMap()->registerAll("AxelCommand", [
            new Feed($this->getConfigValue("feed")[0], isset($this->getConfigValue("feed")[1]) ? $this->getConfigValue("feed")[1] : ""),
            new Heal($this->getConfigValue("heal")[0], isset($this->getConfigValue("heal")[1]) ? $this->getConfigValue("heal")[1] : ""),
            new Furnace($this->getConfigValue("furnace")[0], isset($this->getConfigValue("furnace")[1]) ? $this->getConfigValue("furnace")[1] : "")
        ]);
    }

    public function getConfigValue(string $path, bool $nested = false): mixed
    {
        return $nested ? $this->getConfig()->getNested($path) : $this->getConfig()->get($path);
    }

    public static function getInstance(): Axel
    {
        return self::$main;
    }
}