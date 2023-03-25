<?php

namespace Digueloulou12\SeedPlanter;

use pocketmine\block\Air;
use pocketmine\block\Block;
use pocketmine\block\Farmland;
use pocketmine\block\VanillaBlocks;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerItemUseEvent;
use pocketmine\item\Item;
use pocketmine\item\ItemFactory;
use pocketmine\item\ItemIds;
use pocketmine\player\Player;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\Config;

class SeedPlanter extends PluginBase implements Listener
{
    public function onEnable(): void
    {
        if (!file_exists($this->getDataFolder() . "config.yml")) {
            new Config($this->getDataFolder() . "config.yml", Config::YAML, [
                "# [ID, META, RAY]",
                "items" => [
                    [369, 0, 5],
                    [377, 0, 10]
                ]
            ]);
        }

        $this->getServer()->getPluginManager()->registerEvents($this, $this);
    }

    public function onUse(PlayerItemUseEvent $event): void
    {
        $item = $event->getItem();
        $player = $event->getPlayer();
        if ($this->isSeedPlanter($item)) {
            $ray = $this->getRayByItem($item);
            for ($x = ($player->getPosition()->x - $ray); $x <= ($player->getPosition()->x + $ray); $x++) {
                for ($z = ($player->getPosition()->z - $ray); $z <= ($player->getPosition()->z + $ray); $z++) {
                    $block = $player->getWorld()->getBlockAt($x, $player->getPosition()->y, $z);
                    $seed = $player->getWorld()->getBlockAt($x, $player->getPosition()->y + 1, $z);;
                    if (($block instanceof Farmland) and ($seed instanceof Air)) {
                        $item = $this->getSeedsInInventory($player);
                        if (!is_null($item)) {
                            $player->getWorld()->setBlock($seed->getPosition(), $this->getBlockByItem($item));
                            $player->getInventory()->removeItem($item);
                        }
                    }
                }
            }
        }
    }

    public function isSeedPlanter(Item $item): bool
    {
        foreach ($this->getConfig()->get("items") as $value) {
            if ($item->getId() === $value[0] and $item->getMeta() === $value[1]) {
                return true;
            }
        }
        return false;
    }

    public function getRayByItem(Item $item): int
    {
        foreach ($this->getConfig()->get("items") as $value) {
            if ($item->getId() === $value[0] and $item->getMeta() === $value[1]) {
                return $value[2];
            }
        }
        return 0;
    }

    public function getSeedsInInventory(Player $player): ?Item
    {
        $seeds = [
            ItemIds::SEEDS,
            ItemIds::BEETROOT_SEEDS,
            ItemIds::CARROT,
            ItemIds::POTATO,
        ];

        foreach ($seeds as $id) {
            if ($player->getInventory()->contains(ItemFactory::getInstance()->get($id, 0, 1))) {
                return ItemFactory::getInstance()->get($id, 0, 1);
            }
        }
        return null;
    }

    public function getBlockByItem(Item $item): Block
    {
        return match ($item->getId()) {
            ItemIds::SEEDS => VanillaBlocks::WHEAT(),
            ItemIds::BEETROOT_SEEDS => VanillaBlocks::BEETROOTS(),
            ItemIds::CARROT => VanillaBlocks::CARROTS(),
            ItemIds::POTATO => VanillaBlocks::POTATOES()
        };
    }
}