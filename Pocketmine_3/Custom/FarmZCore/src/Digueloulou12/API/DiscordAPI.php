<?php

namespace Digueloulou12\API;

use CortexPE\DiscordWebhookAPI\Embed;
use CortexPE\DiscordWebhookAPI\Message;
use CortexPE\DiscordWebhookAPI\Webhook;
use Digueloulou12\Main;

class DiscordAPI{
    public function sendMessage(string $msg){
        $msgg = new Message();
        $msgg->setContent($msg);

        $webhook = new Webhook(Main::getConfigAPI()->getConfigValue("webhook"));
        $webhook->send($msgg);
    }

    public function sendEmbed(string $msg, string $title){
        $embed = new Embed();
        $embed->setTitle($title);
        $embed->setColor(Main::getConfigAPI()->getConfigValue("embed_color"));
        $embed->setDescription($msg);

        $msgg = new Message();
        $msgg->addEmbed($embed);

        $webhook = new Webhook(Main::getConfigAPI()->getConfigValue("webhook"));
        $webhook->send($msgg);
    }
}