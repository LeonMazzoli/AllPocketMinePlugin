<?php

namespace Digueloulou12\Events;

use Digueloulou12\Main;
use Digueloulou12\Tasks\CombatTask;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerChatEvent;
use pocketmine\event\player\PlayerCommandPreprocessEvent;

class ChatEvent implements Listener
{
    public static array $mute = [];

    public function onChat(PlayerChatEvent $event)
    {
        if (isset(self::$mute[$event->getPlayer()->getName()])) {
            if (self::$mute[$event->getPlayer()->getName()]["time"] > time()) {
                $event->setCancelled();
                $event->getPlayer()->sendMessage(Main::getConfigAPI()->getConfigValue("mute_no_send_msg"));
                return;
            }
        }

        if ($event->isCancelled()) return;
        Main::getDiscordAPI()->sendMessage(Main::getConfigAPI()->getConfigValue("chat_discord", ["{player}", "{msg}"], [$event->getPlayer()->getName(), $event->getMessage()]));
    }

    public function onCommand(PlayerCommandPreprocessEvent $event)
    {
        if ($event->isCancelled()) return;
        if ($event->getMessage()[0] === "/") {

            if (!empty(CombatTask::$combat[$event->getPlayer()->getName()])) {
                if (!$event->getPlayer()->isOp()) {
                    $event->getPlayer()->sendMessage(Main::getConfigAPI()->getConfigValue("combat_command_msg"));
                    $event->setCancelled(true);
                }
            }

            Main::getDiscordAPI()->sendMessage(Main::getConfigAPI()->getConfigValue("command_discord", ["{player}", "{cmd}"], [$event->getPlayer()->getName(), $event->getMessage()]));
        }
    }
}