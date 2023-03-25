<?php

namespace Digueloulou12;

use pocketmine\entity\Effect;
use pocketmine\entity\EffectInstance;
use pocketmine\event\entity\EntityArmorChangeEvent;
use pocketmine\event\Listener;
use pocketmine\Player;
use pocketmine\plugin\PluginBase;

class ArmorMain extends PluginBase implements Listener
{
    public function onEnable()
    {
        $this->saveResource("config.yml");
        $this->getServer()->getPluginManager()->registerEvents($this, $this);
    }

    public function onArmor(EntityArmorChangeEvent $event)
    {
        $player = $event->getEntity();
        if (!($player instanceof Player)) return;

        $config = $this->getConfig();
        if ($config->exists($event->getNewItem()->getId())){
            foreach ($config->get($event->getNewItem()->getId()) as $name => $value){
                $effect = explode(":", $value);
                $player->addEffect(new EffectInstance(Effect::getEffect($effect[0]), 9999999 * 20, $effect[1] - 1, $effect[2]));
            }
        } elseif ($config->exists($event->getOldItem()->getId())){
            foreach ($config->get($event->getOldItem()->getId()) as $name => $value){
                $effect = explode(":", $value);
                $player->removeEffect($effect[0]);
            }
        }
    }
}