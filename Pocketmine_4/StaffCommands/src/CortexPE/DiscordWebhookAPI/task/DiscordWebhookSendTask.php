<?php

declare(strict_types = 1);

namespace CortexPE\DiscordWebhookAPI\task;

use CortexPE\DiscordWebhookAPI\Message;
use CortexPE\DiscordWebhookAPI\Webhook;
use pocketmine\scheduler\AsyncTask;

class DiscordWebhookSendTask extends AsyncTask
{
    protected Webhook $webhook;
    protected Message $message;

    public function __construct(Webhook $webhook, Message $message)
    {
        $this->webhook = $webhook;
        $this->message = $message;
    }

    public function onRun(): void
    {
        $ch = curl_init($this->webhook->getURL());
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($this->message));
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ["Content-Type: application/json"]);
        $this->setResult(curl_exec($ch));
        curl_close($ch);
    }

    public function onCompletion(): void
    {
        $response = $this->getResult();
    }
}