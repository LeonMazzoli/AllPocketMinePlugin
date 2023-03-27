<?php

namespace Digueloulou12\Outpost;

use Digueloulou12\Outpost\Task\OutpostTask;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\SingletonTrait;

class Outpost extends PluginBase
{
    use SingletonTrait;

    public function onEnable(): void
    {
        self::setInstance($this);
        $this->saveDefaultConfig();

        $this->getScheduler()->scheduleRepeatingTask(new OutpostTask(), 20);
    }
}