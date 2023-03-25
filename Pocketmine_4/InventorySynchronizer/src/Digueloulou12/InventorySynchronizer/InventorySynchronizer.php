<?php

namespace Digueloulou12\InventorySynchronizer;

use Digueloulou12\InventorySynchronizer\API\DatabaseAPI;
use Digueloulou12\InventorySynchronizer\Events\InventorySynchronizerEvents;
use pocketmine\plugin\PluginBase;

class InventorySynchronizer extends PluginBase
{
    private static self $this;

    public function onEnable(): void
    {
        self::$this = $this;
        $this->saveDefaultConfig();
        DatabaseAPI::init();
        $this->getServer()->getPluginManager()->registerEvents(new InventorySynchronizerEvents(), $this);
    }

    public static function getInstance(): self
    {
        return self::$this;
    }
}