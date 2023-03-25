<?php

namespace Digueloulou12\ProtectArea\Events;

use Digueloulou12\ProtectArea\ProtectArea;
use Digueloulou12\ProtectArea\Utils\Utils;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerDeathEvent;
use pocketmine\event\player\PlayerDropItemEvent;
use pocketmine\event\player\PlayerExhaustEvent;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\event\player\PlayerItemUseEvent;
use pocketmine\item\ItemIds;

class PlayerEvents implements Listener
{
    public static array $players = [];

    public function onDeath(PlayerDeathEvent $event): void
    {
        if (ProtectArea::getInstance()->getProtectAreaAPI()->cancel($event->getPlayer(), "drop-death", $event->getPlayer()->getPosition())) $event->setDrops([]);
    }

    public function onDrop(PlayerDropItemEvent $event): void
    {
        if (ProtectArea::getInstance()->getProtectAreaAPI()->cancel($event->getPlayer(), "drop-items", $event->getPlayer()->getPosition())) $event->cancel();
    }

    public function onExhaust(PlayerExhaustEvent $event): void
    {
        if (ProtectArea::getInstance()->getProtectAreaAPI()->cancel($event->getEntity(), "hunger", $event->getEntity()->getPosition())) $event->cancel();
    }

    public function onUse(PlayerItemUseEvent $event): void
    {
        $item = $event->getItem();
        $pos = $event->getPlayer()->getPosition();
        if ($item->getId() === ItemIds::ENDER_PEARL) {
            if (ProtectArea::getInstance()->getProtectAreaAPI()->cancel($event->getPlayer(), "enderpearl", $pos)) $event->cancel();
        } elseif ($item->getId() === ItemIds::EGG) {
            if (ProtectArea::getInstance()->getProtectAreaAPI()->cancel($event->getPlayer(), "egg", $pos)) $event->cancel();
        } elseif ($item->getId() === ItemIds::SNOWBALL) {
            if (ProtectArea::getInstance()->getProtectAreaAPI()->cancel($event->getPlayer(), "snowball", $pos)) $event->cancel();
        } else if (ProtectArea::getInstance()->getProtectAreaAPI()->cancel($event->getPlayer(), "use", $pos)) $event->cancel();
    }

    public function onInteract(PlayerInteractEvent $event): void
    {
        $player = $event->getPlayer();
        if (isset(self::$players[$player->getName()])) {
            $name = self::$players[$player->getName()]["name"];
            if (count(array_keys(self::$players[$player->getName()])) === 1) {
                self::$players[$player->getName()] = ["pos" => ProtectArea::getInstance()->getProtectAreaAPI()->getStringByPosition($event->getBlock()->getPosition()), "name" => $name];
                $player->sendMessage(Utils::getConfigReplace("add_one"));
            } elseif (count(array_keys(self::$players[$player->getName()])) === 2) {
                self::$players[$player->getName()] = ["pos1" => ProtectArea::getInstance()->getProtectAreaAPI()->getStringByPosition($event->getBlock()->getPosition()), "pos" => self::$players[$player->getName()]["pos"], "name" => $name];
                ProtectArea::getInstance()->getProtectAreaAPI()->addArea(
                    self::$players[$player->getName()]["name"],
                    ProtectArea::getInstance()->getProtectAreaAPI()->getPositionByString(self::$players[$player->getName()]["pos"]),
                    ProtectArea::getInstance()->getProtectAreaAPI()->getPositionByString(self::$players[$player->getName()]["pos1"])
                );
                $player->sendMessage(Utils::getConfigReplace("add_area", "{area}", self::$players[$player->getName()]["name"]));
                unset(self::$players[$player->getName()]);
            }
        }
    }
}