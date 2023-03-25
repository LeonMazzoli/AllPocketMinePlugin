<?php

namespace Digueloulou12\RockItem;

use pocketmine\event\Listener;
use pocketmine\event\player\PlayerItemUseEvent;
use pocketmine\math\Vector3;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\Config;

class RockItem extends PluginBase implements Listener
{
    public function onEnable(): void
    {
        if (!file_exists($this->getDataFolder() . "config.yml")) {
            new Config($this->getDataFolder() . "config.yml", Config::YAML, [
                "item" => [369, 0],
                "force" => 3,
                "remove" => true
            ]);
        }

        $this->getServer()->getPluginManager()->registerEvents($this, $this);
    }

    public function onUse(PlayerItemUseEvent $event)
    {
        $item = $event->getItem();
        $ii = $this->getConfig()->get("item");

        if (($item->getId() === $ii[0]) and ($item->getMeta() === $ii[1])) {
            $player = $event->getPlayer();
            $player->setMotion(new Vector3(0, $this->getConfig()->get("force"), 0));
            if ($this->getConfig()->get("remove")) $player->getInventory()->setItemInHand($item->setCount($item->getCount() - 1));
        }
    }
}