<?php

namespace FlyBlock;

use pocketmine\block\Block;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerJumpEvent;
use pocketmine\event\player\PlayerMoveEvent;
use pocketmine\level\Position;
use pocketmine\utils\Config;

class Events implements Listener{
    public function onJump(PlayerJumpEvent $event){
        $config = new Config(FlyMain::getInstance()->getDataFolder() . "config.yml", Config::YAML);
        $player = $event->getPlayer();
        $x = $player->getX();
        $y = $player->getY();
        $z = $player->getZ();
        $block = new Position($x, $y, $z, $player->getLevel());

        if (!isset(Command::$fly[$player->getName()])) return;
        if ($player->isSneaking()) return;
        if (!FlyblockAPI::getInventory($player)) return;
        $id = explode(":", $config->get("block"));
        FlyblockAPI::removeInventory($player);
        $player->getLevel()->setBlock($block, new Block($id[0], $id[1]), false, true);
    }

    public function onMove(PlayerMoveEvent $event){
        $config = new Config(FlyMain::getInstance()->getDataFolder() . "config.yml", Config::YAML);
        $player = $event->getPlayer();

        $x = $player->getX();
        $y = $player->getY() - 1;
        $z = $player->getZ();
        $block = new Position($x, $y, $z, $player->getLevel());

        if (!isset(Command::$fly[$player->getName()])) return;
        if ($player->isSneaking()) return;
        if (!FlyblockAPI::getInventory($player)) return;
        $id = explode(":", $config->get("block"));
        FlyblockAPI::removeInventory($player);
        $player->getLevel()->setBlock($block, new Block($id[0], $id[1]), false, true);
    }
}