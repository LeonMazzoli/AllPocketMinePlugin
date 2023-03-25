<?php

namespace Digueloulou12\Events;

use pocketmine\event\Listener;
use Digueloulou12\API\FactionAPI;
use pocketmine\event\player\PlayerChatEvent;

class ChatEvent implements Listener
{
    public function onChat(PlayerChatEvent $event)
    {
        if ($event->isCancelled()) return;

        if (!empty(FactionAPI::$chat[$event->getPlayer()->getName()])) {
            FactionAPI::sendMessageFaction($event->getMessage(), FactionAPI::getFactionPlayer($event->getPlayer()), $event->getPlayer());
            $event->setCancelled(true);
        }
    }
}