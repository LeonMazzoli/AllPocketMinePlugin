<?php

namespace Digueloulou12;

use pocketmine\data\bedrock\EffectIdMap;
use pocketmine\entity\effect\EffectInstance;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\Config;

class BlockEffects extends PluginBase implements Listener
{
    public function onEnable(): void
    {
        if (!file_exists($this->getDataFolder() . "config.yml")) {
            new Config($this->getDataFolder() . "config.yml", Config::YAML, [
                1 => [
                    "night_vision" => [
                        19, 30, 0, false
                    ]
                ]
            ]);
        }

        $this->getServer()->getPluginManager()->registerEvents($this, $this);
    }

    public function onInteract(PlayerInteractEvent $event)
    {
        $config = $this->getConfig();
        if ($config->exists($event->getBlock()->getId())) {
            foreach ($config->get($event->getBlock()->getId()) as $name => $effect) {
                $event->getPlayer()->getEffects()->add(new EffectInstance(EffectIdMap::getInstance()->fromId($effect[0]), 20 * $effect[1], $effect[2], $effect[3]));
            }
        }
    }
}