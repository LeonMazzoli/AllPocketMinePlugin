<?php

namespace Sanction\Events;

use pocketmine\event\Listener;
use pocketmine\event\player\PlayerChatEvent;
use pocketmine\utils\Config;
use Sanction\Commands\Mute;
use Sanction\SanctionMain;

class Chat implements Listener{
    public function onChat(PlayerChatEvent $event)
    {
        $player = $event->getPlayer();
        $config = new Config(SanctionMain::getInstance()->getDataFolder() . "config.yml", Config::YAML);
        if (isset(Mute::$mute[$player->getName()])) {
            if (mute::$mute[$player->getName()] > time()) {
                $event->setCancelled();
                $player->sendMessage($config->getNested("mute.mess"));
            }
        }
    }
}