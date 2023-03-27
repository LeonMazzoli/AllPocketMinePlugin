<?php

namespace Digueloulou12\BlockBedrock;

use pocketmine\block\Block;
use pocketmine\block\VanillaBlocks;
use pocketmine\event\block\BlockBreakEvent;
use pocketmine\event\Listener;
use pocketmine\math\Vector3;
use pocketmine\plugin\PluginBase;
use pocketmine\scheduler\Task;
use pocketmine\world\World;

class BlockBedrock extends PluginBase implements Listener
{
    public function onEnable(): void
    {
        $this->saveDefaultConfig();
        $this->getServer()->getPluginManager()->registerEvents($this, $this);
    }

    public function onBreak(BlockBreakEvent $event) {
        $player = $event->getPlayer();
        $block = $event->getBlock();
        $world = $player->getWorld();
        $config = $this->getConfig();

        if (isset($config->get("blocks")["{$block->getId()}-{$block->getMeta()}"])) {
            $event->cancel();
            foreach ($block->getDrops($player->getInventory()->getItemInHand()) as $drop) {
                if ($player->getInventory()->canAddItem($drop)) {
                    $player->getInventory()->addItem($drop);
                } else $player->dropItem($drop);
            }
            $world->setBlock($block->getPosition(), VanillaBlocks::BEDROCK());

            $time = $this->getConfig()->get("blocks")["{$block->getId()}-{$block->getMeta()}"];

            $this->getScheduler()->scheduleDelayedTask(new class($world, $block->getPosition(), $block) extends Task {
                private World $world;
                private Vector3 $pos;
                private Block $block;

                public function __construct(World $level, Vector3 $pos, Block $block) {
                    $this->world = $level;
                    $this->pos = $pos;
                    $this->block = $block;
                }

                public function onRun(): void {
                    $this->world->setBlock($this->pos, $this->block);
                    $this->getHandler()->cancel();
                }
            }, 20 * $time);
        }
    }

}