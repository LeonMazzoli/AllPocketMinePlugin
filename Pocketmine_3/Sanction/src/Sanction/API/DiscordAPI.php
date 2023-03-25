<?php

namespace Sanction\API;

use CortexPE\DiscordWebhookAPI\Embed;
use CortexPE\DiscordWebhookAPI\Message;
use CortexPE\DiscordWebhookAPI\Webhook;
use Sanction\SanctionMain;

class DiscordAPI
{
    public static function sendEmbed($title, $desc)
    {
        $web = new Webhook(SanctionMain::getConfigValue("web"));

        $embed = new Embed();
        $embed->setTitle($title);
        $embed->setColor(SanctionMain::getConfigValue("color"));
        $embed->setDescription($desc);

        $message = new Message();
        $message->addEmbed($embed);
        $web->send($message);
    }

    public static function sendMessage(string $content)
    {
        $web = new Webhook(SanctionMain::getConfigValue("web"));
        $message = new Message();
        $message->setContent($content);
        $web->send($message);
    }

    public static function discord(string $content, string $title = "")
    {
        if (SanctionMain::getConfigValue("discord") === true) {
            if (SanctionMain::getConfigValue("web") !== "") {
                if (SanctionMain::getConfigValue("type") === "embed") {
                    self::sendEmbed($title, $content);
                } else self::sendMessage($content);
            }
        }
    }
}