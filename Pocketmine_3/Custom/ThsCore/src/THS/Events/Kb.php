<?php

namespace THS\Events;

use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\event\Listener;
use pocketmine\Player;
use pocketmine\utils\Config;
use THS\Main;

class Kb implements Listener{
    public function onDamage(EntityDamageByEntityEvent $event){
        $config = new Config(Main::getInstance()->getDataFolder()."config.yml",Config::YAML);
        $player = $event->getEntity();
        $world = $player->getLevel()->getName();

        if ($world === "KB") return;

        if ($player instanceof Player){
            if ($config->getNested("kw.$world") !== null){
                $event->setKnockBack($config->getNested("kb.$world"));
            }
        }
    }
}