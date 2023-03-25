<?php

namespace Digueloulou12\HoodHelmet;

use pocketmine\event\inventory\InventoryTransactionEvent;
use pocketmine\event\Listener;
use pocketmine\inventory\ArmorInventory;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\Config;

class HoodHelmet extends PluginBase implements Listener
{
    private array $players = [];

    public function onEnable(): void
    {
        if (!file_exists($this->getDataFolder() . "config.yml")) {
            new Config($this->getDataFolder() . "config.yml", Config::YAML, [
                "helmet_id" => 310
            ]);
        }

        $this->getServer()->getPluginManager()->registerEvents($this, $this);
    }

    public function onInventoryTransaction(InventoryTransactionEvent $event)
    {
        $edit = false;
        foreach ($event->getTransaction()->getInventories() as $inventory) {
            if ($inventory instanceof ArmorInventory) {
                $edit = true;
                break;
            }
        }

        if ($edit) {
            $player = $event->getTransaction()->getSource();
            foreach ($event->getTransaction()->getActions() as $action) {
                $action->execute($player);
            }

            if ($player->getArmorInventory()->getHelmet()->getId() === $this->getConfig()->get("helmet_id")) {
                $this->players[$player->getName()] = $player->getNameTag();
                $player->setNameTag("");
            } elseif (isset($this->players[$player->getName()])) $player->setNameTag($this->players[$player->getName()]);
        }
    }
}