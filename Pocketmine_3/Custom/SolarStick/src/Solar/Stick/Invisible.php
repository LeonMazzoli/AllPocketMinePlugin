<?php

namespace Solar\Stick;

use pocketmine\entity\Effect;
use pocketmine\entity\EffectInstance;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\utils\Config;
use Solar\Stick;

class Invisible implements Listener{
    public function Inter(PlayerInteractEvent $event){
        $config = new Config(Stick::getInstance()->getDataFolder() . "config.yml", Config::YAML);
        $player = $event->getPlayer();
        $action = $event->getAction();
        $stick = $event->getItem();

        if ($action == 1 or $action == 3){
            if ($stick->getId() == $config->get("invisible.id") and $stick->getDamage() == $config->get("invisible.meta")){
                $invi = new EffectInstance(Effect::getEffect("14"), $config->get("invisible.duration") * 20, $config->get("invisible.ampli"), $config->get("invisible.visible"));
                $player->addEffect($invi);
                if ($config->get("pouf") == true){
                    $player->getInventory()->setItemInHand($player->getInventory()->getItemInHand()->setCount($stick->getCount() - 1));
                }
            }
        }
    }
}