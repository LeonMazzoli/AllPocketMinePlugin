<?php

namespace Digueloulou12;

use pocketmine\event\player\PlayerItemConsumeEvent;
use pocketmine\entity\effect\EffectInstance;
use pocketmine\data\bedrock\EffectIdMap;
use pocketmine\plugin\PluginBase;
use pocketmine\event\Listener;

class FoodEffect extends PluginBase implements Listener
{
    public function onEnable(): void
    {
        $this->saveDefaultConfig();
        $this->getServer()->getPluginManager()->registerEvents($this, $this);
    }

    public function onFood(PlayerItemConsumeEvent $event)
    {
        $id = "{$event->getItem()->getId()}-{$event->getItem()->getMeta()}";
        if ($this->getConfig()->exists($id)) {
            foreach ($this->getConfig()->get($id) as $effect) {
                $eff = explode(":", $effect);
                $event->getPlayer()->getEffects()->add(new EffectInstance(EffectIdMap::getInstance()->fromId($eff[0]), $eff[1] * 20, $eff[2], $eff[3]));
            }
        }
    }
}