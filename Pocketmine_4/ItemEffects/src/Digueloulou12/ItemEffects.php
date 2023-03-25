<?php

namespace Digueloulou12;

use pocketmine\data\bedrock\EffectIdMap;
use pocketmine\entity\effect\EffectInstance;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\Config;

class ItemEffects extends PluginBase implements Listener
{
    private array $time = [];

    public function onEnable(): void
    {
        if (!file_exists($this->getDataFolder() . "config.yml")) {
            new Config($this->getDataFolder() . "config.yml", Config::YAML, [
                "369-0" => [
                    "effects" => [
                        "speed" => [1, 20, 3, false]
                    ],
                    "time" => 10,
                    "use" => 5
                ],
                "popup" => "{time} !!",
                "use" => "Use: {use}"
            ]);
        }

        $this->getServer()->getPluginManager()->registerEvents($this, $this);
    }

    public function onInteract(PlayerInteractEvent $event)
    {
        $item = $event->getItem();
        $id = "{$event->getItem()->getId()}-{$event->getItem()->getMeta()}";
        if ($this->getConfig()->exists($id)) {
            if (!isset($this->time[$id][$event->getPlayer()->getName()]) or ($this->time[$id][$event->getPlayer()->getName()] < time())) {
                foreach ($this->getConfig()->get($id)["effects"] as $name => $effect) {
                    $event->getPlayer()->getEffects()->add(new EffectInstance(EffectIdMap::getInstance()->fromId($effect[0]), 20 * $effect[1], $effect[2], $effect[3]));
                }
                $this->time[$id][$event->getPlayer()->getName()] = time() + $this->getConfig()->get($id)["time"];

                if ($item->getNamedTag()->getTag("use") !== null) {
                    if ($item->getNamedTag()->getInt("use") === 0) {
                        $event->getPlayer()->getInventory()->setItemInHand($item->setCount($item->getCount() - 1));
                    } else {
                        $event->getPlayer()->getInventory()->setItemInHand($item->setCount($item->getCount() - 1));
                        $use = $item->getNamedTag()->getInt("use") - 1;
                        $item->getNamedTag()->setInt("use", $use);
                        $item->setLore([str_replace("{use}", $use, $this->getConfig()->get("use"))]);
                        $item->setCount(1);
                        if ($event->getPlayer()->getInventory()->canAddItem($item)) {
                            $event->getPlayer()->getInventory()->addItem($item);
                        } else $event->getPlayer()->getWorld()->dropItem($event->getPlayer()->getPosition(), $item);
                    }
                } else {
                    $event->getPlayer()->getInventory()->setItemInHand($item->setCount($item->getCount() - 1));
                    $item->getNamedTag()->setInt("use", $this->getConfig()->get($id)["use"]);
                    $item->setLore([str_replace("{use}", $this->getConfig()->get($id)["use"], $this->getConfig()->get("use"))]);
                    $item->setCount(1);
                    if ($event->getPlayer()->getInventory()->canAddItem($item)) {
                        $event->getPlayer()->getInventory()->addItem($item);
                    } else $event->getPlayer()->getWorld()->dropItem($event->getPlayer()->getPosition(), $item);
                }
            } else {
                $time = $this->time[$id][$event->getPlayer()->getName()] - time();
                $event->getPlayer()->sendPopup(str_replace("{time}", $time, $this->getConfig()->get("popup")));
            }
        }
    }
}