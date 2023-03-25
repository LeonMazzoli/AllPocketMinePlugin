<?php

namespace Discord\Events;

use CortexPE\DiscordWebhookAPI\Message;
use CortexPE\DiscordWebhookAPI\Webhook;
use Discord\DiscordMain;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\utils\Config;

class Join implements Listener{
    public function onJoin(PlayerJoinEvent $event){
        $config = new Config(DiscordMain::getInstance()->getDataFolder()."config.yml",Config::YAML);
        if ($config->get("join") === false) return;
        $webhook = new Webhook($config->get("webhook"));
        $message = new Message();
        $message->setContent(str_replace(strtolower('{player}'), $event->getPlayer()->getName(), $config->get("join.message")));
        $webhook->send($message);
    }
}