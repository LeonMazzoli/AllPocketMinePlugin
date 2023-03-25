<?php

namespace Digueloulou12;

use pocketmine\plugin\PluginBase;

class ClearLagg extends PluginBase
{
    private static ClearLagg $clearLagg;

    public function onEnable(): void
    {
        self::$clearLagg = $this;
        $this->saveDefaultConfig();
        $this->getScheduler()->scheduleRepeatingTask(new ClearLaggTask($this->getConfig()->get("time")), 20);
    }

    public static function getInstance(): ClearLagg
    {
        return self::$clearLagg;
    }
}