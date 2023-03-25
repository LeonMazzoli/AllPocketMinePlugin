<?php

namespace Digueloulou12\Events;

use Digueloulou12\API\ConfigAPI;
use Digueloulou12\API\FactionAPI;
use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerDeathEvent;
use pocketmine\Player;

class PlayerEvents implements Listener
{
    public function entityDamage(EntityDamageByEntityEvent $event)
    {
        $player = $event->getEntity();
        $sender = $event->getDamager();

        if (!($player instanceof Player)) return;
        if (!($sender instanceof Player)) return;

        if (!FactionAPI::isInFaction($player)) return;
        if (!FactionAPI::isInFaction($sender)) return;

        if (FactionAPI::getFactionPlayer($player) === FactionAPI::getFactionPlayer($sender)) $event->setCancelled(true);
        if (in_array(FactionAPI::getFactionPlayer($player), FactionAPI::getAllyFaction(FactionAPI::getFactionPlayer($sender)))) $event->setCancelled(true);
        if (in_array(FactionAPI::getFactionPlayer($sender), FactionAPI::getAllyFaction(FactionAPI::getFactionPlayer($player)))) $event->setCancelled(true);
    }

    public function onDeath(PlayerDeathEvent $event)
    {
        $cause = $event->getPlayer()->getLastDamageCause();
        if ($cause instanceof EntityDamageByEntityEvent) {
            $damager = $cause->getDamager();
            if ($damager instanceof Player) {
                FactionAPI::addPowerFaction(FactionAPI::getFactionPlayer($damager), ConfigAPI::getConfigValue("power_kill"));
            }
        }

        if (FactionAPI::getPowerFaction(FactionAPI::getFactionPlayer($event->getPlayer())) >= ConfigAPI::getConfigValue("power_death")) {
            FactionAPI::removePowerFaction(FactionAPI::getFactionPlayer($event->getPlayer()), ConfigAPI::getConfigValue("power_death"));
        } else FactionAPI::removePowerFaction(FactionAPI::getFactionPlayer($event->getPlayer()), FactionAPI::getPowerFaction(FactionAPI::getFactionPlayer($event->getPlayer())));
    }
}