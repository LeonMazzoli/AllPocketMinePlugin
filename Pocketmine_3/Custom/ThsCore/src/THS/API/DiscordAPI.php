<?php

namespace THS\API;

use CortexPE\DiscordWebhookAPI\Embed;
use CortexPE\DiscordWebhookAPI\Message;
use CortexPE\DiscordWebhookAPI\Webhook;

class DiscordAPI{
    public static function sendMessage($messageSend){
        $webhook = new Webhook("https://discord.com/api/webhooks/765205065203318795/q5m-lL63fLn95BfJACpmyh3AiznuXno7g-yfgeUglMLNrNRYzz_f9i1XA2J8bqZZpvVK");
        $message = new Message();
        $message->setContent($messageSend);
        $webhook->send($message);
    }

    public static function sendEmbed($title, $messageSend){
        $webhook = new Webhook("https://discord.com/api/webhooks/765205065203318795/q5m-lL63fLn95BfJACpmyh3AiznuXno7g-yfgeUglMLNrNRYzz_f9i1XA2J8bqZZpvVK");
        $embed = new Embed();
        $embed->setTitle($title);
        $message = new Message();
        $embed->setDescription($messageSend);
        $message->addEmbed($embed);
        $webhook->send($message);
    }
}