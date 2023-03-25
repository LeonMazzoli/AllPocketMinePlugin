<?php

namespace Digueloulou12;

use pocketmine\entity\Effect;
use pocketmine\entity\EffectInstance;
use pocketmine\event\block\BlockBreakEvent;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\item\Item;
use pocketmine\plugin\PluginBase;

class Mercure extends PluginBase implements Listener{
    public function onEnable()
    {
        $this->getServer()->getPluginManager()->registerEvents($this, $this);
    }

    public function onBreak(BlockBreakEvent $event)
    {
        if ($event->getBlock()->getId() === 231){
            if(mt_rand(0, 90) === 1){
                $event->setDrops([Item::get(467, 0, 1)]);
                $event->getPlayer()->sendMessage("§cMercure > §fTu as obtenu une pépite de mercure !");
            } else $event->setDrops([]);
        }
    }

    public function onUse(PlayerInteractEvent $event)
    {
        if (($event->getItem()->getId() === 325) and ($event->getItem()->getDamage() === 5)) {
            $event->getPlayer()->addEffect(new EffectInstance(Effect::getEffect(Effect::SPEED), 20 * 15, 1, false));
            $event->getPlayer()->addEffect(new EffectInstance(Effect::getEffect(Effect::REGENERATION), 20 * 5, 1, false));
            $event->getPlayer()->getInventory()->setItemInHand($event->getPlayer()->getInventory()->getItemInHand()->setCount($event->getItem()->getCount() - 1));
        }
    }
}