<?php

namespace Digueloulou12\Events;

use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\event\Listener;
use Digueloulou12\Money;

class PlayersEvents implements Listener
{
    public function onJoin(PlayerJoinEvent $event)
    {
        Money::createPlayer($event->getPlayer());
    }
}