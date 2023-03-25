<?php

namespace Digueloulou12;

use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\event\Listener;
use pocketmine\player\Player;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\Config;

class ReversePosition extends PluginBase implements Listener
{
    public function onEnable(): void
    {
        if (!file_exists($this->getDataFolder() . "config.yml")) {
            new Config($this->getDataFolder() . "config.yml", Config::YAML, [
                "item" => [369, 0],
                "uses" => 2,
                "lore" => "Use: §c{uses}"
            ]);
        }

        $this->getServer()->getPluginManager()->registerEvents($this, $this);
    }

    public function onDamage(EntityDamageByEntityEvent $event)
    {
        $sender = $event->getDamager();
        $player = $event->getEntity();

        if (!($sender instanceof Player)) return;
        if (!($player instanceof Player)) return;

        $item = $sender->getInventory()->getItemInHand();
        if (($item->getId() === $this->getConfig()->get("item")[0]) and
            ($item->getMeta() === $this->getConfig()->get("item")[1])) {

            $add = false;
            if ($item->getCount() > 1) {
                $sender->getInventory()->setItemInHand($item->setCount($item->getCount() - 1));
                $add = true;
            }

            if ($item->getNamedTag()->getTag("switch_pos") === null) {
                $item->getNamedTag()->setInt("switch_pos", $this->getConfig()->get("uses"));
            }

            $item->getNamedTag()->setInt("switch_pos", $item->getNamedTag()->getInt("switch_pos") - 1);
            $item->setLore([str_replace("{uses}", $item->getNamedTag()->getInt("switch_pos"), $this->getConfig()->get("lore"))]);

            if ($add) {
                if ($item->getNamedTag()->getInt("switch_pos") !== 0) {
                    if ($sender->getInventory()->canAddItem($item)) {
                        $sender->getInventory()->addItem($item->setCount(1));
                    } else $sender->getWorld()->dropItem($sender->getPosition(), $item->setCount(1));
                }
            } else $sender->getInventory()->setItemInHand($item->setCount(1));

            if (($item->getNamedTag()->getInt("switch_pos") === 0) and !$add) $sender->getInventory()->setItemInHand($item->setCount($item->getCount() - 1));

            $pos = $sender->getPosition();
            $pos_ = $player->getPosition();

            $player->teleport($pos);
            $sender->teleport($pos_);
        }
    }
}