<?php

namespace Digueloulou12\Events;

use Digueloulou12\Commands\Staff;
use Digueloulou12\Main;
use Digueloulou12\Tasks\CombatTask;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerQuitEvent;
use pocketmine\event\player\PlayerTransferEvent;
use pocketmine\Server;

class QuitEvent implements Listener{
    public function onQuit(PlayerQuitEvent $event){
        $event->setQuitMessage("");
        Server::getInstance()->broadcastTip(Main::getConfigAPI()->getConfigValue("quit_msg", [strtolower("{player}")], [$event->getPlayer()->getName()]));
        Main::getDiscordAPI()->sendMessage(Main::getConfigAPI()->getConfigValue("quit_msg_discord", [strtolower("{player}")], [$event->getPlayer()->getName()]));

        if (!empty(Staff::$staff[$event->getPlayer()->getName()])){
            $event->getPlayer()->getInventory()->setContents(Staff::$staff[$event->getPlayer()->getName()]["inv"]);
            $event->getPlayer()->getArmorInventory()->setContents(Staff::$staff[$event->getPlayer()->getName()]["armor"]);
            unset(Staff::$staff[$event->getPlayer()->getName()]);
        }

        if (!empty(CombatTask::$combat[$event->getPlayer()->getName()])) $event->getPlayer()->kill();
    }

    public function onTransfer(PlayerTransferEvent $event){
        if (!empty(Staff::$staff[$event->getPlayer()->getName()])){
            $event->getPlayer()->getInventory()->setContents(Staff::$staff[$event->getPlayer()->getName()]["inv"]);
            $event->getPlayer()->getArmorInventory()->setContents(Staff::$staff[$event->getPlayer()->getName()]["armor"]);
            unset(Staff::$staff[$event->getPlayer()->getName()]);
        }
    }
}