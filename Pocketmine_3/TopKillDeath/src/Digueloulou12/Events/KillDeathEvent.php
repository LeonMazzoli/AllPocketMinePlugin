<?php

namespace Digueloulou12\Events;

use Digueloulou12\TopMain;
use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerDeathEvent;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\Player;
use pocketmine\utils\Config;

class KillDeathEvent implements Listener
{
    public function onDeath(PlayerDeathEvent $event)
    {
        $kill = new Config(TopMain::getInstance()->getDataFolder() . "kill.json", Config::JSON);
        $death = new Config(TopMain::getInstance()->getDataFolder() . "death.json", Config::JSON);

        $death->set($event->getPlayer()->getName(), $death->get($event->getPlayer()->getName()) + 1);

        $cause = $event->getPlayer()->getLastDamageCause();
        if ($cause instanceof EntityDamageByEntityEvent) {
            $damager = $cause->getDamager();
            if ($damager instanceof Player) $kill->set($damager->getName(), $kill->get($damager->getName()) + 1);
        }

        $kill->save();
        $death->save();
    }

    public function onJoin(PlayerJoinEvent $event)
    {
        $kill = new Config(TopMain::getInstance()->getDataFolder() . "kill.json", Config::JSON);
        $death = new Config(TopMain::getInstance()->getDataFolder() . "death.json", Config::JSON);

        if (!$kill->exists($event->getPlayer()->getName())) $kill->set($event->getPlayer()->getName(), 0);
        if (!$death->exists($event->getPlayer()->getName())) $death->set($event->getPlayer()->getName(), 0);

        $kill->save();
        $death->save();
    }
}