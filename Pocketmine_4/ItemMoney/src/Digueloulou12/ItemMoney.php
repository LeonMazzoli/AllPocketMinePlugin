<?php

namespace Digueloulou12;

use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\event\player\PlayerItemUseEvent;
use pocketmine\plugin\PluginBase;
use pocketmine\event\Listener;
use pocketmine\plugin\Plugin;
use pocketmine\player\Player;
use pocketmine\Server;

class ItemMoney extends PluginBase implements Listener
{
    public array $time = [];
    public Plugin $plugin;

    public function onEnable(): void
    {
        $this->saveDefaultConfig();

        $economy = $this->getConfig()->get("economy");
        if (Server::getInstance()->getPluginManager()->getPlugin($economy) === null) {
            $this->getLogger()->alert("THIS PLUGIN IS DISABLE BECAUSE NOT FOUND PLUGIN $economy !");
            $this->getServer()->getPluginManager()->disablePlugin($this);
            return;
        }

        $this->plugin = Server::getInstance()->getPluginManager()->getPlugin($economy);

        $this->getServer()->getPluginManager()->registerEvents($this, $this);
    }

    public function onInteract(PlayerInteractEvent $event)
    {
        $this->useItem($event->getPlayer());
    }

    public function onUse(PlayerItemUseEvent $event)
    {
        $this->useItem($event->getPlayer());
    }

    public function useItem(Player $player): void
    {
        $item = $player->getInventory()->getItemInHand();
        $id = $item->getId() . "-" . $item->getMeta();
        if (isset($this->getConfig()->get("items")[$id])) {
            if ((empty($this->time[$player->getName()])) or ($this->time[$player->getName()] < time())) {
                $money = $this->getConfig()->get("items")[$id][array_rand($this->getConfig()->get("items")[$id])];
                if (($this->plugin->getName() === "MoneyAPI") or ($this->plugin->getName() === "EconomyAPI")) {
                    $this->plugin->addMoney($player, $money);
                } elseif ($this->plugin->getName() === "BedrockEconomy") {
                    $this->plugin->getAPI()->addToPlayerBalance($player->getName(), $money);
                }
                $player->sendPopup(str_replace("{money}", $money, $this->getConfig()->get("message")));
                $player->getInventory()->removeItem($item->pop());
                $this->time[$player->getName()] = time() + 1;
            }
        }
    }
}