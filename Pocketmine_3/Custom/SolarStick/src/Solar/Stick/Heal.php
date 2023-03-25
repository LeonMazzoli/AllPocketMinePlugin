<?php

namespace Solar\Stick;

use pocketmine\event\Listener;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\Player;
use pocketmine\utils\Config;
use Solar\Stick;

class Heal implements Listener{
    public function onInteract(PlayerInteractEvent $event){
        $config = new Config(Stick::getInstance()->getDataFolder() . "config.yml", Config::YAML);
        $player = $event->getPlayer();
        $action = $event->getAction();
        $stick = $event->getItem();

        if ($action == 1 or $action == 3){
            if ($stick->getId() == $config->get("heal.id") and $stick->getDamage() == $config->get("heal.meta")){
                if ($player->getHealth() <= 20){
                    $player->setHealth($player->getMaxHealth());
                    if ($config->get("pouuuf") == true){
                        $player->getInventory()->setItemInHand($player->getInventory()->getItemInHand()->setCount($stick->getCount() - 1));
                    }
                }
            }
        }
    }
}