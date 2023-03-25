<?php

namespace Solar\Stick;

use pocketmine\event\Listener;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\utils\Config;
use Solar\Stick;

class Feed implements Listener{
    public function onInter(PlayerInteractEvent $event){
        $config = new Config(Stick::getInstance()->getDataFolder() . "config.yml", Config::YAML);
        $player = $event->getPlayer();
        $action = $event->getAction();
        $stick = $event->getItem();

        if ($action == 1 or $action == 3){
            if ($stick->getId() == $config->get("feed.id") and $stick->getDamage() == $config->get("feed.meta")){
                if ($player->getFood() <= 20){
                    $player->setFood($player->getMaxFood());
                    if ($config->get("pouuf") == true){
                        $player->getInventory()->setItemInHand($player->getInventory()->getItemInHand()->setCount($stick->getCount() - 1));
                    }
                }
            }
        }
    }
}