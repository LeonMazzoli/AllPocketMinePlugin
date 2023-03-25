<?php

namespace Digueloulou12;

use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\event\player\PlayerItemUseEvent;
use pocketmine\plugin\PluginBase;
use pocketmine\event\Listener;
use pocketmine\player\Player;
use pocketmine\utils\Config;

class ItemCooldown extends PluginBase implements Listener
{
    public array $cooldown = [];
    public Config $config;

    public function onEnable(): void
    {
        if (!file_exists($this->getDataFolder() . "config.yml")) {
            $this->config = new Config($this->getDataFolder() . "config.yml", Config::YAML, [
                "369-0" => 10,
                "280-0" => 30,
                "popup" => "You must wait {time} second(s) !"
            ]);
        } else $this->config = $this->getConfig();

        $this->getServer()->getPluginManager()->registerEvents($this, $this);
    }

    public function onUse(PlayerItemUseEvent $event)
    {
        if ($this->cooldownItem($event->getPlayer())) $event->cancel();
    }

    public function onInteract(PlayerInteractEvent $event)
    {
        if ($this->cooldownItem($event->getPlayer())) $event->cancel();
    }

    public function cooldownItem(Player $player): bool
    {
        $item = $player->getInventory()->getItemInHand();
        $id = $item->getId() . "-" . $item->getMeta();
        if ($this->config->exists($id)) {
            if ((empty($this->cooldown[$player->getName()][$id])) or ($this->cooldown[$player->getName()][$id] < time())) {
                $this->cooldown[$player->getName()][$id] = time() + $this->config->get($id);
                return false;
            } else {
                $time = $this->cooldown[$player->getName()][$id] - time();
                $player->sendPopup(str_replace("{time}", $time, $this->config->get("popup")));
                return true;
            }
        }
        return false;
    }
}