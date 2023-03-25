<?php

namespace THS\Items;

use pocketmine\block\Block;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\item\ItemIds;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\nbt\tag\IntTag;
use pocketmine\nbt\tag\StringTag;
use pocketmine\tile\Tile;

class EnderChest implements Listener{
    public function onUse(PlayerInteractEvent $event){
        if ($event->getItem()->getId() === ItemIds::ENDER_CHEST){
            $nbt = new CompoundTag("", [new StringTag("id", Tile::CHEST), new StringTag("CustomName", "EnderChest"), new IntTag("x", (int)floor($event->getPlayer()->x)), new IntTag("y", (int)floor($event->getPlayer()->y) - 4), new IntTag("z", (int)floor($event->getPlayer()->z))]);
            $tile = Tile::createTile("EnderChest", $event->getPlayer()->getLevel(), $nbt);
            $block = Block::get(Block::ENDER_CHEST);
            $block->x = (int)$tile->x;
            $block->y = (int)$tile->y;
            $block->z = (int)$tile->z;
            $block->level = $tile->getLevel();
            $block->level->sendBlocks([$event->getPlayer()], [$block]);
            if ($tile instanceof \pocketmine\tile\EnderChest) {
                $event->getPlayer()->getEnderChestInventory()->setHolderPosition($tile);
                $event->getPlayer()->addWindow($event->getPlayer()->getEnderChestInventory());
            }
        }
    }
}