<?php

namespace Assassin\Events;

use Assassin\Commands\Spy;
use Assassin\Main;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerCommandPreprocessEvent;
use pocketmine\utils\Config;

class SpyEvent implements Listener{
    public function onChat(PlayerCommandPreprocessEvent $event){
        $config = new Config(Main::getInstance()->getDataFolder() . "config.yml", Config::YAML);
        $player = $event->getPlayer();
        $message = $event->getMessage();
        if($message[0] == "/") {
            if(!empty(Spy::$spy)) {
                foreach (spy::$spy as $espion) {
                    if ($message[0] == "/") {
                        $espion->sendMessage($player->getName() . " -> " . $message);
                    }
                }
            }
            if ($config->get("consoles") === true){
                Main::getInstance()->getLogger()->info($player->getName() . " -> " . $message);
            }
        }
    }
}