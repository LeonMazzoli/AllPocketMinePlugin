<?php

namespace Digueloulou12;

use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\event\Listener;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\Config;

class NoFallDamage extends PluginBase implements Listener
{
    public function onEnable(): void
    {
        if (!file_exists($this->getDataFolder() . "config.yml")) {
            new Config($this->getDataFolder() . "config.yml", Config::YAML, ["worlds" => ["world"]]);
        }

        $this->getServer()->getPluginManager()->registerEvents($this, $this);
    }

    public function onDamage(EntityDamageEvent $event)
    {
        if ($event->getCause() === EntityDamageEvent::CAUSE_FALL) {
            if (in_array($event->getEntity()->getPosition()->getWorld()->getFolderName(), $this->getConfig()->get("worlds"))) $event->cancel();
        }
    }
}