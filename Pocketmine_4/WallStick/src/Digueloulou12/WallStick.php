<?php

namespace Digueloulou12;

use pocketmine\event\Listener;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\Config;

class WallStick extends PluginBase implements Listener
{
    public function onEnable(): void
    {
        if (!file_exists($this->getDataFolder() . "config.yml")) {
            new Config($this->getDataFolder() . "config.yml", Config::YAML,
                [
                    "item" => [369, 0],
                    "no_possible" => "You can't pass!",
                    "possible" => "You have been teleported!"
                ]
            );
        }

        $this->getServer()->getPluginManager()->registerEvents($this, $this);
    }

    public function onInteract(PlayerInteractEvent $event)
    {
        $config = $this->getConfig();
        if (($event->getItem()->getId() === $config->get("item")[0]) and ($event->getItem()->getMeta() === $config->get("item")[1])) {
            $player = $event->getPlayer();
            $block = $event->getBlock();
            $pos = null;
            $id = null;
            $y = intval($player->getPosition()->getY()) + 1;
            if ($y == intval($block->getPosition()->getY())) {
                $direction = $player->getLocation()->getYaw();
                $direction %= 360;
                if (45 <= $direction and $direction < 135) {
                    for ($x = $block->getPosition()->getX(); $id !== 0; $x--) {
                        $b = $player->getWorld()->getBlockAt($x, $player->getPosition()->getY(), $player->getPosition()->getZ());
                        $id = ($b->getId() === 0 or $b->getId() === 7) ? 0 : $b->getId();
                        $pos = $b->getPosition();
                    }
                } elseif (135 <= $direction and $direction < 225) {
                    for ($z = $block->getPosition()->getZ(); $id !== 0; $z--) {
                        $b = $player->getWorld()->getBlockAt($block->getPosition()->getX(), $player->getPosition()->getY(), $z);
                        $id = ($b->getId() === 0 or $b->getId() === 7) ? 0 : $b->getId();
                        $pos = $b->getPosition();
                    }
                } elseif (225 <= $direction and $direction < 315) {
                    for ($x = $block->getPosition()->getX(); $id !== 0; $x++) {
                        $b = $player->getWorld()->getBlockAt($x, $player->getPosition()->getY(), $player->getPosition()->getZ());
                        $id = ($b->getId() === 0 or $b->getId() === 7) ? 0 : $b->getId();
                        $pos = $b->getPosition();
                    }
                } else {
                    for ($z = $block->getPosition()->getZ(); $id !== 0; $z++) {
                        $b = $player->getWorld()->getBlockAt($block->getPosition()->getX(), $player->getPosition()->getY(), $z);
                        $id = ($b->getId() === 0 or $b->getId() === 7) ? 0 : $b->getId();
                        $pos = $b->getPosition();
                    }
                }
            }
            if (!is_null($pos) and $player->getWorld()->getBlock($pos)->getId() !== 7) {
                $player->teleport($pos);
                $player->sendMessage($config->get("possible"));
            } else $player->sendMessage($config->get("no_possible"));
        }
    }
}