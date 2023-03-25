<?php

namespace Assassin\Events;

use Assassin\Main;
use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\event\Listener;
use pocketmine\utils\Config;

class Kb implements Listener{
    public function onTap(EntityDamageByEntityEvent $event){
        $player = $event->getEntity();
        $config = new Config(Main::getInstance()->getDataFolder() . "config.yml", Config::YAML);
        if ($player->getLevel()->getName() === "Lobby"){
            $event->setKnockBack($config->get("kb"));
        }
    }
}