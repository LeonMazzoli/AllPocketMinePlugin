<?php

namespace Digueloulou12\Events;

use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\event\player\PlayerItemUseEvent;
use pocketmine\event\player\PlayerRespawnEvent;
use pocketmine\event\player\PlayerDeathEvent;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\item\ItemFactory;
use pocketmine\event\Listener;
use pocketmine\player\Player;
use Digueloulou12\RyanSword;
use pocketmine\Server;

class SwordEvents implements Listener
{
    public function onJoin(PlayerJoinEvent $event)
    {
        $item = RyanSword::getRyanSword()->getConfig()->get("sword");
        $sword = ItemFactory::getInstance()->get($item[0], $item[1], $item[2]);
        $event->getPlayer()->getInventory()->setItem($item[3], $sword);
    }

    public function onUse(PlayerItemUseEvent $event)
    {
        $item = RyanSword::getRyanSword()->getConfig()->get("sword");
        if ($event->getItem()->getId() === $item[0]) {
            if ($event->getItem()->getMeta() === $item[1]) {
                $event->getPlayer()->teleport(Server::getInstance()->getWorldManager()->getWorldByName(RyanSword::getRyanSword()->getConfig()->get("world"))->getSafeSpawn());
                RyanSword::getRyanSword()->takeKit($event->getPlayer());
            }
        }
    }

    public function onDeath(PlayerDeathEvent $event)
    {
        $cause = $event->getEntity()->getLastDamageCause();
        if ($cause instanceof EntityDamageByEntityEvent) {
            $sender = $cause->getDamager();
            if ($sender instanceof Player) {
                RyanSword::getRyanSword()->takeKit($sender);
            }
        }
    }

    public function onRespawn(PlayerRespawnEvent $event)
    {
        $event->getPlayer()->teleport(Server::getInstance()->getWorldManager()->getDefaultWorld()->getSafeSpawn());
    }
}