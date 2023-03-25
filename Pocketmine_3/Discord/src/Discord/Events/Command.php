<?php

namespace Discord\Events;

use CortexPE\DiscordWebhookAPI\Message;
use CortexPE\DiscordWebhookAPI\Webhook;
use Discord\DiscordMain;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerCommandPreprocessEvent;
use pocketmine\utils\Config;

class Command implements Listener{
    public function onCommand(PlayerCommandPreprocessEvent $event){
        $config = new Config(DiscordMain::getInstance()->getDataFolder()."config.yml",Config::YAML);
        if ($config->get("death") === false) return;
        $command = $event->getMessage();
        if ($command[0] !== "/") return;
        $webhook = new Webhook($config->get("webhook"));
        $message = new Message();
        $message->setContent(str_replace([strtolower('{player}'), strtolower('{command}')], [$event->getPlayer()->getName(), $command], $config->get("command.message")));
        $webhook->send($message);
    }
}