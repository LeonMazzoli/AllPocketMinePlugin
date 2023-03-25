<?php

namespace Digueloulou12;

use pocketmine\event\block\BlockGrowEvent;
use pocketmine\plugin\PluginBase;
use pocketmine\event\Listener;

class TerkapsPlugin extends PluginBase implements Listener
{
    public function onEnable(): void
    {
        $this->saveDefaultConfig();
        $this->getServer()->getPluginManager()->registerEvents($this, $this);
    }

    public function onGrow(BlockGrowEvent $event)
    {
        if ($this->getConfig()->exists($event->getBlock()->getId())) {
            if (in_array($event->getBlock()->getPosition()->getWorld()->getFolderName(), $this->getConfig()->get($event->getBlock()->getId()))) {
                $event->cancel();
            }
        }
    }
}