<?php

namespace CustomKb;

use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\event\Listener;
use pocketmine\utils\Config;

class CustomKbEvent implements Listener
{
    public function onDamage(EntityDamageByEntityEvent $event)
    {
        $config = new Config(CustomMain::getInstance()->getDataFolder() . "config.yml", Config::YAML);
        $world = $event->getEntity()->getLevel()->getName();
        if ($config->getNested("kb.$world") === null) return;
        $event->setKnockBack($config->getNested("kb.$world") * floatval(7));
    }
}