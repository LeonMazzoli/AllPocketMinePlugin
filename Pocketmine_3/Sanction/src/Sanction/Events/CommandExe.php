<?php

namespace Sanction\Events;

use pocketmine\event\Listener;
use pocketmine\event\player\PlayerCommandPreprocessEvent;
use pocketmine\utils\Config;
use Sanction\Commands\Mute;
use Sanction\SanctionMain;

class CommandExe implements Listener
{
    public function onCommand(PlayerCommandPreprocessEvent $event)
    {
        $config = new Config(SanctionMain::getInstance()->getDataFolder() . "config.yml", Config::YAML);
        $message = $event->getMessage();
        $player = $event->getPlayer();
        if ($message[0] === "/") {
            if (isset(Mute::$mute[$player->getName()])) {
                if (mute::$mute[$player->getName()] > time()) {
                    if (in_array(strtolower($message), $config->get("command"))) {
                        $event->setCancelled(true);
                        $player->sendMessage($config->getNested("mute.mess"));
                    }
                }
            }
        }
    }
}