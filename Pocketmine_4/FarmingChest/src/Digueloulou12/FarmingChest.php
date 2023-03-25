<?php

namespace Digueloulou12;

use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\block\BlockFactory;
use pocketmine\block\tile\Chest;
use pocketmine\item\ItemFactory;
use pocketmine\event\Listener;
use pocketmine\utils\Config;
use pocketmine\math\Vector3;

class FarmingChest implements Listener
{
    public function onUse(PlayerInteractEvent $event)
    {
        $config = new Config(MainFarm::getInstance()->getDataFolder() . "config.yml", Config::YAML);
        if ($event->getBlock()->getId() === (int)$config->get("farmingchest")) {
            $chest = $event->getBlock()->getPosition()->getWorld()->getTile(new Vector3($event->getBlock()->getPosition()->x, $event->getBlock()->getPosition()->y, $event->getBlock()->getPosition()->z));
            if (!($chest instanceof Chest)) return;
            for ($x = ($event->getBlock()->getPosition()->x - $config->get("ray")); $x <= ($event->getBlock()->getPosition()->x + $config->get("ray")); $x++) {
                for ($z = ($event->getBlock()->getPosition()->z - $config->get("ray")); $z <= ($event->getBlock()->getPosition()->z + $config->get("ray")); $z++) {
                    $block = $event->getBlock()->getPosition()->getWorld()->getBlockAt($x, $event->getBlock()->getPosition()->y, $z);
                    switch ($block->getId() . ":" . $block->getMeta()) {
                        # WHEAT
                        case "59:7":
                            if ($config->get("wheat") === true) {
                                $chest->getInventory()->addItem(ItemFactory::getInstance()->get(296, 0, 1));
                                $block->getPosition()->getWorld()->setBlock($block->getPosition(), BlockFactory::getInstance()->get(59, 0));

                                if ($config->get("seeds") === true) {
                                    $chest->getInventory()->addItem(ItemFactory::getInstance()->get(295, 0, mt_rand(0, 2)));
                                }
                            }
                            break;
                        # POTATO
                        case "142:7":
                            if ($config->get("potato") === true) {
                                $chest->getInventory()->addItem(ItemFactory::getInstance()->get(392, 0, mt_rand(1, 4)));
                                $block->getPosition()->getWorld()->setBlock($block->getPosition(), BlockFactory::getInstance()->get(142, 0));
                            }
                            break;
                        # CARROT
                        case "141:7":
                            if ($config->get("carrot") === true) {
                                $chest->getInventory()->addItem(ItemFactory::getInstance()->get(391, 0, mt_rand(1, 4)));
                                $block->getPosition()->getWorld()->setBlock($block->getPosition(), BlockFactory::getInstance()->get(141, 0));
                            }
                            break;
                        # BEETROOT
                        case "244:7":
                            if ($config->get("beetroot") === true) {
                                $chest->getInventory()->addItem(ItemFactory::getInstance()->get(457, 0, mt_rand(1, 4)));
                                $block->getPosition()->getWorld()->setBlock($block->getPosition(), BlockFactory::getInstance()->get(244, 0));

                                if ($config->get("seeds") === true) {
                                    $chest->getInventory()->addItem(ItemFactory::getInstance()->get(458, 0, mt_rand(0, 2)));
                                }
                            }
                            break;
                        case "115:3":
                            if ($config->get("nether_wart") === true) {
                                $chest->getInventory()->addItem(ItemFactory::getInstance()->get(372, 0, mt_rand(2, 4)));
                                $block->getPosition()->getWorld()->setBlock($block->getPosition(), BlockFactory::getInstance()->get(115, 0));
                            }
                            break;
                    }
                }
            }
        }
    }
}