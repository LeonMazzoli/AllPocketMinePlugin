<?php

namespace Digueloulou12\Events;

use pocketmine\event\player\PlayerChatEvent;
use pocketmine\event\player\PlayerJoinEvent;
use Digueloulou12\Commands\Mute;
use Digueloulou12\Task\BanTask;
use pocketmine\event\Listener;
use Digueloulou12\Staff;

class StaffEvents implements Listener
{
    public function onJoin(PlayerJoinEvent $event)
    {
        if (Staff::getInstance()->isBannedPlayer($event->getPlayer())) new BanTask($event->getPlayer());
    }

    public function onChat(PlayerChatEvent $event)
    {
        if (!empty(Mute::$mute[$event->getPlayer()->getName()]) and Mute::$mute[$event->getPlayer()->getName()] > time()) {
            $time = Mute::$mute[$event->getPlayer()->getName()];
            $day = Staff::getInstance()->getRemainingTime($time, "day");
            $hour = Staff::getInstance()->getRemainingTime($time, "hour");
            $min = Staff::getInstance()->getRemainingTime($time, "minute");
            $second = Staff::getInstance()->getRemainingTime($time, "second");
            $event->getPlayer()->sendMessage(Staff::getInstance()->getConfigReplace("no_chat", ["{day}", "{hour}", "{minute}", "{second}"], [$day, $hour, $min, $second]));
            $event->cancel();
        }
    }
}