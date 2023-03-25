<?php

namespace Report\API;

use CortexPE\DiscordWebhookAPI\Embed;
use CortexPE\DiscordWebhookAPI\Message;
use CortexPE\DiscordWebhookAPI\Webhook;
use Report\ReportMain;

class DiscordAPI{
    public static function sendMessage(string $messagee){
        $web = new Webhook(ReportMain::getInstance()->getConfigValue("webhook"));
        $message = new Message();
        $message->setContent($messagee);
        $web->send($message);
    }

    public static function sendEmbed(string $content){
        $embed = new Embed();
        $embed->setTitle(ReportMain::getInstance()->getConfigValue("title_embed"));
        $embed->setColor(ReportMain::getInstance()->getConfigValue("color"));
        $embed->setDescription($content);

        $message = new Message();
        $message->addEmbed($embed);

        $web = new Webhook(ReportMain::getInstance()->getConfigValue("webhook"));
        $web->send($message);
    }
}