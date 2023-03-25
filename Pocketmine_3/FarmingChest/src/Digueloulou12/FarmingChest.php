<?php

namespace Digueloulou12;

use pocketmine\block\Block;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\item\Item;
use pocketmine\math\Vector3;
use pocketmine\tile\Chest;
use pocketmine\utils\Config;

class FarmingChest implements Listener{
    public function onUse(PlayerInteractEvent $event){
        $chest = $event->getBlock()->getLevel()->getTile(new Vector3($event->getBlock()->x, $event->getBlock()->y, $event->getBlock()->z));
        $config = new Config(MainFarm::getInstance()->getDataFolder()."config.yml",Config::YAML);

        if ($event->getBlock()->getId() !== $config->get("farmingchest")) return;
        if (!($chest instanceof Chest)) return;

        for ($x = ($event->getBlock()->x - $config->get("ray")); $x <= ($event->getBlock()->x + $config->get("ray")); $x++) {
            for ($z = ($event->getBlock()->z - $config->get("ray")); $z <= ($event->getBlock()->z + $config->get("ray")); $z++) {
                $block = $event->getBlock()->getLevel()->getBlockAt($x, $event->getBlock()->y, $z);
                switch ($block->getId().":".$block->getDamage()){
                    # WHEAT
                    case "59:7":
                        if ($config->get("wheat") === true){
                            $chest->getInventory()->addItem(Item::get(Item::WHEAT, 0, 1));
                            $block->getLevel()->setBlock($block, Block::get(Block::WHEAT_BLOCK, 0));

                            if ($config->get("seeds") === true){
                                $chest->getInventory()->addItem(Item::get(Item::SEEDS, 0, mt_rand(0, 2)));
                            }
                        }
                        break;
                    # POTATO
                    case "142:7":
                        if ($config->get("potato") === true){
                            $chest->getInventory()->addItem(Item::get(Item::POTATO, 0, mt_rand(1, 4)));
                            $block->getLevel()->setBlock($block, Block::get(Block::POTATO_BLOCK, 0));
                        }
                        break;
                    # CARROT
                    case "141:7":
                        if ($config->get("carrot") === true){
                            $chest->getInventory()->addItem(Item::get(Item::CARROT, 0, mt_rand(1, 4)));
                            $block->getLevel()->setBlock($block, Block::get(Block::CARROT_BLOCK, 0));
                        }
                        break;
                    # BEETROOT
                    case "244:7":
                        if ($config->get("beetroot") === true){
                            $chest->getInventory()->addItem(Item::get(Item::BEETROOT, 0, mt_rand(1, 4)));
                            $block->getLevel()->setBlock($block, Block::get(Block::BEETROOT_BLOCK, 0));

                            if ($config->get("seeds") === true){
                                $chest->getInventory()->addItem(Item::get(Item::BEETROOT_SEEDS, 0, mt_rand(0, 2)));
                            }
                        }
                        break;
                    case "115:3":
                        if ($config->get("nether_wart") === true){
                            $chest->getInventory()->addItem(Item::get(Item::NETHER_WART, 0, mt_rand(2, 4)));
                            $block->getLevel()->setBlock($block, Block::get(Block::NETHER_WART_PLANT, 0));
                        }
                        break;
                }
            }
        }
    }
}