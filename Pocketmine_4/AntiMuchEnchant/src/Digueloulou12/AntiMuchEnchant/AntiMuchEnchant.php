<?php

namespace Digueloulou12\AntiMuchEnchant;

use pocketmine\data\bedrock\EnchantmentIdMap;
use pocketmine\event\inventory\InventoryOpenEvent;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerItemHeldEvent;
use pocketmine\item\Item;
use pocketmine\plugin\PluginBase;

class AntiMuchEnchant extends PluginBase implements Listener
{
    public function onEnable(): void
    {
        $this->saveDefaultConfig();
        $this->getServer()->getPluginManager()->registerEvents($this, $this);
    }

    public function onInventoryOpen(InventoryOpenEvent $event): void
    {
        $edit = false;
        $player = $event->getPlayer();
        $inventory = $event->getInventory();

        foreach ($inventory->getContents() as $slot => $item) {
            if ($this->isMuchItem($item)) {
                $inventory->removeItem($item);
                $edit = true;
            }
        }

        if ($edit) {
            var_dump("{$player->getName()} s'est vu retiré des items avec des enchantements interdits !");
            $player->sendMessage("§cDes items avec des enchantements interdit étaient présents dans l'inventaire que vous avez ouvert !\nIls ont dont était rétiré !");
        }
    }

    public function onItemHeld(PlayerItemHeldEvent $event): void
    {
        $player = $event->getPlayer();
        $item = $event->getItem();
        if ($this->isMuchItem($item)) $player->getInventory()->removeItem($item);
    }

    public function isMuchItem(Item $item): bool
    {
        $config = $this->getConfig();
        foreach ($item->getEnchantments() as $enchantment) {
            $id = EnchantmentIdMap::getInstance()->toId($enchantment->getType());
            if (($config->exists($id)) and ($enchantment->getLevel() > $config->get($id))) {
                return true;
            }
        }
        return false;
    }
}