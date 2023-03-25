<?php

namespace Kill;

use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\event\player\PlayerDeathEvent;
use pocketmine\plugin\PluginBase;
use pocketmine\event\Listener;
use pocketmine\player\Player;
use pocketmine\Server;

class Money extends PluginBase implements Listener
{
    public function onEnable(): void
    {
        $this->saveDefaultConfig();

        $eco = $this->getConfig()->get("eco");
        if (Server::getInstance()->getPluginManager()->getPlugin($eco) === null) {
            $this->getLogger()->info("KILLMONEY DISABLE, NO PLUGIN $eco");
            Server::getInstance()->getPluginManager()->disablePlugin($this);
            return;
        }

        $this->getServer()->getPluginManager()->registerEvents($this, $this);
    }

    public function onDeath(PlayerDeathEvent $event)
    {
        $player = $event->getEntity();
        $cause = $player->getLastDamageCause();

        if ($cause instanceof EntityDamageByEntityEvent) {
            $sender = $cause->getDamager();
            if ($sender instanceof Player) {
                $pl = Server::getInstance()->getPluginManager()->getPlugin($this->getConfig()->get("eco"));
                if ($this->getConfig()->get("chance") == true) {
                    if (mt_rand(1, $this->getConfig()->get("chance.money")) === 1) {
                        $pl->addMoney($sender, $this->getConfig()->get("money"));
                    }
                } else $pl->addMoney($sender, $this->getConfig()->get("money"));
            }
        }
    }
}