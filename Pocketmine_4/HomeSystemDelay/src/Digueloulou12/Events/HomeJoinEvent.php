<?php

namespace Digueloulou12\Events;

use Digueloulou12\API\HomeAPI;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerJoinEvent;

class HomeJoinEvent implements Listener
{
    public function onJoin(PlayerJoinEvent $event)
    {
        HomeAPI::createPlayer($event->getPlayer());
    }
}