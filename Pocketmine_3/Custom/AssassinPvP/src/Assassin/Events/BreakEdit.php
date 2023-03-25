<?php

namespace Assassin\Events;

use Assassin\Commands\Edit;
use Assassin\Main;
use pocketmine\event\block\BlockBreakEvent;
use pocketmine\event\Listener;
use pocketmine\utils\Config;

class BreakEdit implements Listener{
    public function onBreak(BlockBreakEvent $event){
        $config = new Config(Main::getInstance()->getDataFolder() . "world.yml", Config::YAML);
        $player = $event->getPlayer();
        $world = $player->getLevel()->getName();

        if (!$event->isCancelled()){
            if ($config->get($world) != null){
                if ($config->get($world) === "non"){
                    $event->setCancelled(true);
                }
            }
        }
        if (isset(Edit::$edit[$player->getName()])){
            $event->setCancelled(true);
        }
    }
}