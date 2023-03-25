<?php

namespace Digueloulou12\Events;

use Digueloulou12\API\SkyblockAPI;
use Digueloulou12\Commands\Staff;
use Digueloulou12\Main;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\event\player\PlayerPreLoginEvent;
use pocketmine\Server;

class JoinEvent implements Listener
{
    public function onJoin(PlayerJoinEvent $event)
    {
        $event->setJoinMessage("");
        Main::getDiscordAPI()->sendMessage(Main::getConfigAPI()->getConfigValue("join_msg_discord", [strtolower("{player}")], [$event->getPlayer()->getName()]));

        if ($event->getPlayer()->hasPlayedBefore()) {
            Server::getInstance()->broadcastTip(Main::getConfigAPI()->getConfigValue("join_msg", [strtolower("{player}")], [$event->getPlayer()->getName()]));
        } else Server::getInstance()->broadcastMessage(Main::getConfigAPI()->getConfigValue("join_msg_new", [strtolower("{player}")], [$event->getPlayer()->getName()]));

        if (SkyblockAPI::isInIsland($event->getPlayer())) {
            if (!Server::getInstance()->isLevelLoaded(SkyblockAPI::getIslandPlayer($event->getPlayer()))) Server::getInstance()->loadLevel(SkyblockAPI::getIslandPlayer($event->getPlayer()));
        }

        if (!Main::$players->exists($event->getPlayer()->getName())) {
            $info = [
                "Money" => Main::$config->get("default_money"),
                "Death" => 0,
                "Kill" => 0,
                "Mute" => [],
                "Kick" => [],
                "Ban" => []
            ];
            Main::$players->set($event->getPlayer()->getName(), $info);
            Main::$players->save();
        }

        if (!empty(Staff::$freeze[$event->getPlayer()->getName()])){
            $event->getPlayer()->setImmobile(true);
        }
    }

    public function onPreLogin(PlayerPreLoginEvent $event)
    {
        if (!$event->getPlayer()->isWhitelisted()) $event->getPlayer()->close(Main::getConfigAPI()->getConfigValue("whitelist"));
    }
}