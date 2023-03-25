<?php

namespace Digueloulou12\Events;

use Digueloulou12\API\HomeAPI;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerJoinEvent;

class Events implements Listener
{
    public function onJoin(PlayerJoinEvent $event)
    {
        HomeAPI::createPlayer($event->getPlayer());
    }
}