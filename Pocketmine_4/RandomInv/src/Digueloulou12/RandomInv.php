<?php

namespace Digueloulou12;

use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\event\Listener;
use pocketmine\player\Player;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\Config;

class RandomInv extends PluginBase implements Listener
{
    public function onEnable(): void
    {
        if (!file_exists($this->getDataFolder() . "config.yml")) {
            new Config($this->getDataFolder() . "config.yml", Config::YAML, [
                "id" => [369, 0],
                "use_item" => true
            ]);
        }

        $this->getServer()->getPluginManager()->registerEvents($this, $this);
    }

    public function onDamage(EntityDamageByEntityEvent $event)
    {
        $player = $event->getEntity();
        $sender = $event->getDamager();

        if (!($player instanceof Player)) return;
        if (!($sender instanceof Player)) return;

        if (($sender->getInventory()->getItemInHand()->getId() === $this->getConfig()->get("id")[0]) and
            ($sender->getInventory()->getItemInHand()->getMeta() === $this->getConfig()->get("id")[1])) {
            $inventory = $player->getInventory()->getContents();
            shuffle($inventory);
            $player->getInventory()->setContents($inventory);

            if ($this->getConfig()->get("use_item")) {
                $sender->getInventory()->setItemInHand($sender->getInventory()->getItemInHand()->setCount($sender->getInventory()->getItemInHand()->getCount() - 1));
            }
        }
    }
}