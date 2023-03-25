<?php

namespace HealStick;

use pocketmine\event\Listener;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\utils\Config;

class HealStickItem implements Listener{
    public $healtime = [];
    public function onInteract(PlayerInteractEvent $event){
        $player = $event->getPlayer();
        $item = $event->getItem();
        $config = new Config(HealMain::getInstance()->getDataFolder()."config.yml",Config::YAML);

        if ($item->getId() === $config->get("id")){
            if ($event->getAction() === 1 or $event->getAction() === 3){
                if (empty($this->healtime[$player->getName()]) or $this->healtime[$player->getName()] < time()){
                    $this->healtime[$player->getName()] = time() + $config->get("time");
                    $player->setHealth($player->getHealth() + $config->get("heal"));
                    if ($config->get("dispawn") === true){
                        $player->getInventory()->setItemInHand($player->getInventory()->getItemInHand()->setCount($item->getCount() - 1));
                    }
                }else{
                    $temps = $this->healtime[$player->getName()] - time();
                    $player->sendTip(str_replace(strtolower("{time}"), $temps, $config->get("cooldown")));
                }
            }
        }
    }
}