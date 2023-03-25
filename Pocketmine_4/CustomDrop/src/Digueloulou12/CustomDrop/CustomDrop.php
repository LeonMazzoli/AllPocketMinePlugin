<?php

namespace Digueloulou12\CustomDrop;

use pocketmine\event\block\BlockBreakEvent;
use pocketmine\event\Listener;
use pocketmine\item\Item;
use pocketmine\item\ItemFactory;
use pocketmine\plugin\PluginBase;

class CustomDrop extends PluginBase implements Listener
{
    public function onEnable(): void
    {
        $this->saveDefaultConfig();
        $this->getServer()->getPluginManager()->registerEvents($this, $this);
    }

    public function onBreak(BlockBreakEvent $event)
    {
        $block = $event->getBlock();
        $path = "{$block->getId()}-{$block->getMeta()}";
        if ($this->getConfig()->exists($path)) {
            $event->setDrops($this->getItems($this->getConfig()->get($path)));
        }
    }

    /** @return Item[] */
    public function getItems(array $array): array
    {
        $arr = [];
        foreach ($array as $i) {
            $i = explode("-", $i);
            $arr[] = ItemFactory::getInstance()->get($i[0], $i[1] ?? 0, $i[2] ?? 1);
        }
        return $arr;
    }
}