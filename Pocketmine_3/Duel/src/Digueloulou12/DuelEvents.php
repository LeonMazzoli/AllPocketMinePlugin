<?php

namespace Digueloulou12;

use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerCommandPreprocessEvent;
use pocketmine\event\player\PlayerQuitEvent;
use pocketmine\Player;

class DuelEvents implements Listener
{
    public function onQuit(PlayerQuitEvent $event)
    {
        $player = $event->getPlayer();
        if (!empty(DuelAPI::$players[$player->getName()])) {
            DuelAPI::stopGame();
        }
    }

    public function onDamage(EntityDamageByEntityEvent $event)
    {
        $player = $event->getEntity();
        if (!($player instanceof Player)) return;

        if (!empty(DuelAPI::$god[$player->getName()])) {
            $event->setCancelled(true);
        }
    }

    public function onDamage2(EntityDamageEvent $event)
    {
        $victim = $event->getEntity();
        if (!($victim instanceof Player)) return;

        $cause = $victim->getLastDamageCause();

        $damager = null;
        if ($cause instanceof EntityDamageByEntityEvent) $damager = $cause->getDamager(); else if ($event->getCause() === EntityDamageEvent::CAUSE_PROJECTILE) $damager = $event->getEntity()->getOwningEntity();

        if (!($damager instanceof Player)) return;

        if ($event->getFinalDamage() >= $victim->getHealth()) {
            if (!empty(DuelAPI::$players[$victim->getName()])) {
                $event->setCancelled(true);
                DuelAPI::finishGame($damager);
            }
        }
    }

    public function onCommand(PlayerCommandPreprocessEvent $event)
    {
        if (!empty(DuelAPI::$players[$event->getPlayer()->getName()])) {
            if ($event->getMessage()[0] === "/") {
                if (in_array(strtolower($event->getMessage()), MainDuel::$config->get("teleportation_commands"))) {
                    $event->setCancelled(true);
                }
            }
        }
    }
}