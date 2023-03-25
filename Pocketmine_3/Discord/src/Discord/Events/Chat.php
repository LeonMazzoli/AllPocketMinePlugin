<?php

namespace Discord\Events;

use CortexPE\DiscordWebhookAPI\Message;
use CortexPE\DiscordWebhookAPI\Webhook;
use Discord\DiscordMain;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerChatEvent;
use pocketmine\utils\Config;

class Chat implements Listener{
    public function onChat(PlayerChatEvent $event){
        $config = new Config(DiscordMain::getInstance()->getDataFolder()."config.yml",Config::YAML);
        if ($config->get("message") === false) return;
        $webhook = new Webhook($config->get("webhook"));
        $message = new Message();
        $message->setContent(str_replace([strtolower('{player}'), strtolower('{message}')], [$event->getPlayer()->getName(), $event->getMessage()], $config->get("chat.message")));
        $webhook->send($message);
    }
}