<?php

namespace Digueloulou12;

use pocketmine\item\Item;
use pocketmine\item\ItemIds;
use pocketmine\utils\Config;
use pocketmine\event\Listener;
use pocketmine\plugin\PluginBase;
use pocketmine\event\player\PlayerInteractEvent;

class CompressMain extends PluginBase implements Listener
{
    public function onEnable()
    {
        $this->saveResource("config.yml");
        $this->getServer()->getPluginManager()->registerEvents($this, $this);
    }

    public function onUse(PlayerInteractEvent $event)
    {
        // Configs
        // $config = new Config($this->getDataFolder() . "config.yml", Config::YAML);
        // if ($event->getBlock()->getId() . ":" . $event->getBlock()->getDamage() === $config->get("compresseur")) {
        //            if ($config->getNested("loot." . $event->getItem()->getId() . ":" . $event->getItem()->getDamage()) !== null) {
        //                $loot = explode(":", $config->get("loot")[$event->getItem()->getId() . ":" . $event->getItem()->getDamage()]);
        //                $rand = mt_rand(1, (int)$loot[3]);
        //                $event->setCancelled(true);
        //                if ($rand === 2) {
        //                    $item = Item::get((int)$loot[0], (int)$loot[1], (int)$loot[2]);
        //                    if ($event->getPlayer()->getInventory()->canAddItem($item)) {
        //                        $event->setCancelled(true);
        //                        $event->getPlayer()->sendMessage($loot[5]);
        //                        $event->getPlayer()->getInventory()->addItem($item);
        //                        $event->getPlayer()->getInventory()->setItemInHand($event->getPlayer()->getInventory()->getItemInHand()->setCount($event->getItem()->getCount() - 1));
        //                    }
        //                }
        //            }
        //        }

        // For FireRush
        if ($event->getBlock()->getId() === 1) {
            if ($event->getItem()->getId() === ItemIds::EMERALD) {
                $rand = mt_rand(1, 500);
                if ($rand === 2) {
                    $event->getPlayer()->getInventory()->addItem(Item::get(Item::SOUL_SAND, 0, 1));
                    $event->getPlayer()->sendMessage("§9Tu a obtenue 1 terre enchantée !");
                }
                $event->getPlayer()->getInventory()->setItemInHand($event->getPlayer()->getInventory()->getItemInHand()->setCount($event->getItem()->getCount() - 1));
            } elseif ($event->getItem()->getId() === ItemIds::BEETROOT) {
                $rand = mt_rand(1, 100);
                if ($rand === 2) {
                    $event->getPlayer()->sendMessage("§9Vous venez d'obtenir 64 fioles d'xp");
                    $event->getPlayer()->getInventory()->addItem(Item::get(Item::EXPERIENCE_BOTTLE, 0, 64));
                }
                $event->getPlayer()->getInventory()->setItemInHand($event->getPlayer()->getInventory()->getItemInHand()->setCount($event->getItem()->getCount() - 1));
            }
        }
    }
}