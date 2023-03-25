<?php

namespace Solar\Stick;

use pocketmine\entity\Effect;
use pocketmine\entity\EffectInstance;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\utils\Config;
use Solar\Stick;

class Speed implements Listener{
    public function onInterac(PlayerInteractEvent $event){
        $config = new Config(Stick::getInstance()->getDataFolder() . "config.yml", Config::YAML);
        $player = $event->getPlayer();
        $action = $event->getAction();
        $stick = $event->getItem();

        if ($action == 1 or $action == 3){
            if ($stick->getId() == $config->get("speed.id") and $stick->getDamage() == $config->get("speed.meta")){
                $speed = new EffectInstance(Effect::getEffect("1"), $config->get("speed.duration") * 20, $config->get("speed.ampli"), $config->get("speed.visible"));
                $player->addEffect($speed);
                if ($config->get("pouuuuf") == true){
                    $player->getInventory()->setItemInHand($player->getInventory()->getItemInHand()->setCount($stick->getCount() - 1));
                }
            }
        }
    }
}