<?php

namespace Discord\Events;

use CortexPE\DiscordWebhookAPI\Message;
use CortexPE\DiscordWebhookAPI\Webhook;
use Discord\DiscordMain;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerQuitEvent;
use pocketmine\utils\Config;

class Leave implements Listener{
    public function onLeave(PlayerQuitEvent $event){
        $config = new Config(DiscordMain::getInstance()->getDataFolder()."config.yml",Config::YAML);
        if ($config->get("leave") === false) return;
        $webhook = new Webhook($config->get("webhook"));
        $message = new Message();
        $message->setContent(str_replace(strtolower('{player}'), $event->getPlayer()->getName(), $config->get("leave.message")));
        $webhook->send($message);
    }
}