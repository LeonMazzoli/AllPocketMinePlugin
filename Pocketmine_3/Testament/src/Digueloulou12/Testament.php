<?php

namespace Digueloulou12;

use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerDeathEvent;
use pocketmine\item\Item;
use pocketmine\math\Vector3;
use pocketmine\Player;
use pocketmine\plugin\PluginBase;

class Testament extends PluginBase implements Listener
{
    public function onEnable()
    {
        $this->saveResource("config.yml");
        $this->getServer()->getPluginManager()->registerEvents($this, $this);
    }

    public function onDeath(PlayerDeathEvent $event)
    {
        $cause = $event->getPlayer()->getLastDamageCause();
        if ($cause instanceof EntityDamageByEntityEvent) {
            $damager = $cause->getDamager();
            if ($damager instanceof Player) {
                $i = explode(":", $this->getConfig()->get("id"));
                $item = Item::get($i[0], $i[1], 1)->setCustomName(str_replace(["{victim}", "{killer}"], [(string)$event->getPlayer()->getName(), (string)$damager->getName()], $this->getConfig()->get("name")));
                if ($damager->getInventory()->canAddItem($item)) {
                    $damager->getInventory()->addItem($item);
                } else $this->getServer()->getLevelByName($damager->getLevel()->getName())->dropItem(new Vector3($damager->getX(), $damager->getY(), $damager->getZ()), $item);
            }
        }
    }
}