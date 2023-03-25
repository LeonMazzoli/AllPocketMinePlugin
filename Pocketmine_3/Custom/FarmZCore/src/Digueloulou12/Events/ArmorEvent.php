<?php

namespace Digueloulou12\Events;

use Digueloulou12\Main;
use pocketmine\entity\Effect;
use pocketmine\entity\EffectInstance;
use pocketmine\event\entity\EntityArmorChangeEvent;
use pocketmine\event\Listener;
use pocketmine\Player;

class ArmorEvent implements Listener{
    public function onArmor(EntityArmorChangeEvent $event)
    {
        $player = $event->getEntity();
        if (!($player instanceof Player)) return;

        $config = Main::$config;
        if ($config->getNested("armor.{$event->getNewItem()->getId()}")){
            foreach ($config->getNested("armor.{$event->getNewItem()->getId()}") as $name => $value){
                $effect = explode(":", $value);
                $player->addEffect(new EffectInstance(Effect::getEffect($effect[0]), 9999999 * 20, $effect[1] - 1, $effect[2]));
            }
        } elseif ($config->getNested("armor.{$event->getOldItem()->getId()}")){
            foreach ($config->getNested("armor.{$event->getOldItem()->getId()}") as $name => $value){
                $effect = explode(":", $value);
                $player->removeEffect($effect[0]);
            }
        }
    }
}