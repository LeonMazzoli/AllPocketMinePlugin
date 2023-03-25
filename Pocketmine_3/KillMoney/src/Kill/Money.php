<?php

namespace Kill;

use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\event\player\PlayerDeathEvent;
use pocketmine\plugin\PluginBase;
use pocketmine\event\Listener;
use pocketmine\Player;
use pocketmine\Server;

class Money extends PluginBase implements Listener
{
    public function onEnable()
    {
        @mkdir($this->getDataFolder());
        if (!file_exists($this->getDataFolder() . "config.yml")) {
            $this->saveResource("config.yml");
        }
        $this->getLogger()->info("KillMoney on by Digueloulou12");
        $this->getServer()->getPluginManager()->registerEvents($this, $this);

        if ($this->getConfig()->get("eco") == "EconomyAPI") {
            if (Server::getInstance()->getPluginManager()->getPlugin("EconomyAPI")) {
                $this->getLogger()->info("Le plugin a été activé avec les actions de EconomyAPI");
            } else {
                $this->getLogger()->info("Le plugin est désactivé suite a la configuration 'eco'");
                Server::getInstance()->getPluginManager()->disablePlugin($this);
            }
        } elseif ($this->getConfig()->get("eco") == "FacEssential") {
            if (Server::getInstance()->getPluginManager()->getPlugin("FacEssential")) {
                $this->getLogger()->info("Le plugin a été activé avec les actions de FacEssential");
            } else {
                $this->getLogger()->info("Le plugin est désactivé suite a la configuration 'eco'");
                Server::getInstance()->getPluginManager()->disablePlugin($this);
            }
        }
    }

    public function onDisable()
    {
        $this->getLogger()->info("KillMoney off by Digueloulou12");
    }

    public function onDeath(PlayerDeathEvent $event)
    {
        $player = $event->getEntity();
        $cause = $player->getLastDamageCause();

        if ($cause instanceof EntityDamageByEntityEvent) {
            $sender = $cause->getDamager();
            if ($sender instanceof Player) {
                if ($this->getConfig()->get("chance") == true) {
                    switch (mt_rand(1, $this->getConfig()->get("chance.money"))) {
                        case 1:
                            if ($this->getConfig()->get("eco") == "EconomyAPI") {
                                $ecoapi = Server::getInstance()->getPluginManager()->getPlugin("EconomyAPI");
                                $ecoapi->addMoney($sender, $this->getConfig()->get("money"));
                            } elseif ($this->getConfig()->get("eco") == "FacEssential") {
                                $fac = Server::getInstance()->getPluginManager()->getPlugin("EconomyAPI");
                                $fac->addMoney($sender, $this->getConfig()->get("money"));
                            }
                            break;
                    }
                } else {
                    if ($this->getConfig()->get("eco") == "EconomyAPI") {
                        $ecoapi = Server::getInstance()->getPluginManager()->getPlugin("EconomyAPI");
                        $ecoapi->addMoney($sender, $this->getConfig()->get("money"));
                    } elseif ($this->getConfig()->get("eco") == "FacEssential") {
                        $fac = Server::getInstance()->getPluginManager()->getPlugin("EconomyAPI");
                        $fac->addMoney($sender, $this->getConfig()->get("money"));
                    }
                }
            }
        }
    }
}