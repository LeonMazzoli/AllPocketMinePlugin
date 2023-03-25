<?php

namespace Digueloulou12;

use Digueloulou12\Events\UnclaimEvents;
use pocketmine\plugin\PluginBase;
use pocketmine\item\ItemFactory;
use pocketmine\item\Item;

class Unclaim extends PluginBase
{
    private static Unclaim $main;

    public function onEnable(): void
    {
        self::$main = $this;
        $this->saveDefaultConfig();
        $this->getServer()->getPluginManager()->registerEvents(new UnclaimEvents(), $this);
    }

    public function getUnclaimFinderItem(): Item
    {
        return ItemFactory::getInstance()->get($this->getConfig()->get("id")[0], $this->getConfig()->get("id")[1], 1);
    }

    public static function getInstance(): Unclaim
    {
        return self::$main;
    }
}