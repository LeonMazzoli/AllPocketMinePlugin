<?php

namespace Digueloulou12\Events;

use Digueloulou12\Main;
use Digueloulou12\Tasks\CombatTask;
use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerDeathEvent;
use pocketmine\Player;

class DeathEvent implements Listener
{
    public function onDeath(PlayerDeathEvent $event)
    {
        $cause = $event->getPlayer()->getLastDamageCause();
        if ($cause instanceof EntityDamageByEntityEvent) {
            $damager = $cause->getDamager();

            if ($damager instanceof Player\Player) {
                Main::$players->setNested("{$damager->getName()}.Kill", Main::$players->get($event->getPlayer()->getName())["Kill"] + 1);
            }
        }

        Main::$players->setNested("{$event->getPlayer()->getName()}.Death", Main::$players->get($event->getPlayer()->getName())["Death"] + 1);
        Main::$players->save();

        if (!empty(CombatTask::$combat[$event->getPlayer()->getName()])) unset(CombatTask::$combat[$event->getPlayer()->getName()]);
    }
}