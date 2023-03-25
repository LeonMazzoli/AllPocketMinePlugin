<?php

namespace Digueloulou12\Events;

use Digueloulou12\Commands\Staff;
use Digueloulou12\Forms\StaffForms;
use Digueloulou12\Main;
use Digueloulou12\Tasks\CombatTask;
use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\event\Listener;
use pocketmine\Player;

class EntityDamageEvent implements Listener
{
    public function onTap(EntityDamageByEntityEvent $event)
    {
        $player = $event->getDamager();
        $sender = $event->getEntity();

        if (!($sender instanceof Player) or !($player instanceof Player)) return;

        if (!empty(Staff::$staff[$player->getName()])) {
            StaffForms::form($player, $sender);
            $event->setCancelled(true);
        }

        if ($event->isCancelled()) return;
        foreach ([$event->getDamager(), $event->getEntity()] as $players) {
            $players->sendMessage(Main::getConfigAPI()->getConfigValue("combat_start"));
            CombatTask::$combat[$players->getName()] = time();
        }
    }
}