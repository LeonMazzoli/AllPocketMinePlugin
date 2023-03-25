<?php

namespace Digueloulou12\Welcome\Event;

use Digueloulou12\Welcome\Welcome;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerJoinEvent;

class WelcomeEvent implements Listener
{
    public function onJoin(PlayerJoinEvent $event)
    {
        if (!$event->getPlayer()->hasPlayedBefore()) {
            Welcome::getInstance()->setNewPlayer($event->getPlayer()->getName());
        }
    }
}