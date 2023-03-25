<?php

namespace Digueloulou12;

use pocketmine\data\bedrock\EnchantmentIdMap;
use pocketmine\event\player\PlayerDeathEvent;
use pocketmine\data\bedrock\EnchantmentIds;
use pocketmine\plugin\PluginBase;
use pocketmine\event\Listener;
use pocketmine\item\ItemIds;

class AntiDropItem extends PluginBase implements Listener
{
    public function onEnable(): void
    {
        $this->getServer()->getPluginManager()->registerEvents($this, $this);
    }

    public function onDeath(PlayerDeathEvent $event)
    {
        $drops = [];

        $delIds = [ItemIds::IRON_HELMET, ItemIds::IRON_CHESTPLATE, ItemIds::IRON_LEGGINGS, ItemIds::IRON_BOOTS, ItemIds::IRON_SWORD];
        foreach ($event->getPlayer()->getInventory()->getContents() as $slot => $item) {
            if (in_array($item->getId(), $delIds)) {
                if (!($item->hasEnchantment(EnchantmentIdMap::getInstance()->fromId(EnchantmentIds::PROTECTION), 1)) and !($item->hasEnchantment(EnchantmentIdMap::getInstance()->fromId(EnchantmentIds::SHARPNESS), 1))) $drops[] = $item;
            } else $drops[] = $item;
        }
        $event->setDrops($drops);
    }
}