<?php

namespace Digueloulou12;

use pocketmine\event\Listener;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\item\ItemIds;
use pocketmine\plugin\PluginBase;

class MainAxe extends PluginBase implements Listener
{
    public function onEnable()
    {
        $this->getServer()->getPluginManager()->registerEvents($this, $this);
    }

    public function onUse(PlayerInteractEvent $event)
    {
        if ($event->getItem()->getId() === ItemIds::DIAMOND_AXE) {
            $event->getPlayer()->sendMessage("Vous ne pouvez pas taper ce joueur avec cet item !");
            $event->setCancelled(true);
        }
    }
}