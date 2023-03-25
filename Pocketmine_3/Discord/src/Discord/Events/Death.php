<?php

namespace Discord\Events;

use CortexPE\DiscordWebhookAPI\Message;
use CortexPE\DiscordWebhookAPI\Webhook;
use Discord\DiscordMain;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerDeathEvent;
use pocketmine\utils\Config;

class Death implements Listener{
    public function onDeath(PlayerDeathEvent $event){
        $config = new Config(DiscordMain::getInstance()->getDataFolder()."config.yml",Config::YAML);
        if ($config->get("death") === false) return;
        $webhook = new Webhook($config->get("webhook"));
        $message = new Message();
        $message->setContent(str_replace(strtolower('{player}'), $event->getPlayer()->getName(), $config->get("death.message")));
        $webhook->send($message);
    }
}