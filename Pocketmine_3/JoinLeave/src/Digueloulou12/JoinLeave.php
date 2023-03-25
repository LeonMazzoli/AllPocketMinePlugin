<?php

namespace Digueloulou12;

use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\event\player\PlayerQuitEvent;
use pocketmine\plugin\PluginBase;
use pocketmine\event\Listener;

class JoinLeave extends PluginBase implements Listener
{
    public function onEnable()
    {
        $this->saveDefaultConfig();
        $this->getServer()->getPluginManager()->registerEvents($this, $this);
    }

    public function onJoin(PlayerJoinEvent $event)
    {
        if ($this->getConfig()->get("join")) {
            $event->setJoinMessage("");
            $msg = str_replace("{player}", $event->getPlayer()->getName(), $this->getConfig()->get("msg"));
            switch ($this->getConfig()->get("type")) {
                case "tip":
                    $this->getServer()->broadcastTip($msg);
                    break;
                case "popup":
                    $this->getServer()->broadcastPopup($msg);
                    break;
                case "title":
                    $this->getServer()->broadcastTitle($msg);
                    break;
                default:
                    $event->setJoinMessage($msg);
                    break;
            }
        }
    }

    public function onQuit(PlayerQuitEvent $event)
    {
        if ($this->getConfig()->get("leave")) {
            $event->setQuitMessage("");
            $msg = str_replace("{player}", $event->getPlayer()->getName(), $this->getConfig()->get("msg_"));
            switch ($this->getConfig()->get("type_")) {
                case "tip":
                    $this->getServer()->broadcastTip($msg);
                    break;
                case "popup":
                    $this->getServer()->broadcastPopup($msg);
                    break;
                case "title":
                    $this->getServer()->broadcastTitle($msg);
                    break;
                default:
                    $event->setQuitMessage($msg);
                    break;
            }
        }
    }
}