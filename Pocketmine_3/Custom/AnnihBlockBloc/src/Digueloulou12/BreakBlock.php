<?php

namespace Digueloulou12;

use pocketmine\event\block\BlockBreakEvent;
use pocketmine\event\Listener;
use pocketmine\utils\Config;

class BreakBlock implements Listener
{
    public function onBreak(BlockBreakEvent $event)
    {
        $config = new Config(MainBlock::getInstance()->getDataFolder() . "config.yml", Config::YAML);

        $block = $event->getBlock();
        $player = $event->getPlayer();
        $world = $player->getLevel()->getName();

        foreach ($config->get("world") as $value => $key) {
            if ($value === $world) {
                if ($key["break"] === false) {
                    if (in_array($block->getId() . ":" . $block->getDamage(), $key["id"])) {
                        $event->setCancelled(true);
                    }
                } elseif ($key["break"] === true) {
                    if (!in_array($block->getId() . ":" . $block->getDamage(), $key["id"])) {
                        $event->setCancelled(true);
                    }
                }
            }
        }
    }
}