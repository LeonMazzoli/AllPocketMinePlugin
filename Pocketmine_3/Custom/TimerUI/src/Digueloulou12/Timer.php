<?php

namespace Digueloulou12;

use pocketmine\event\player\PlayerInteractEvent;
use jojoe77777\FormAPI\SimpleForm;
use pocketmine\plugin\PluginBase;
use pocketmine\event\Listener;
use pocketmine\Player;
use pocketmine\Server;

class Timer extends PluginBase implements Listener
{
    private static Timer $main;
    public static array $player = [];
    public static array $cooldown = [];

    public function onEnable()
    {
        self::$main = $this;
        $this->saveDefaultConfig();

        if (Server::getInstance()->getPluginManager()->getPlugin("EconomyAPI") === null) {
            Server::getInstance()->getPluginManager()->disablePlugin($this);
            $this->getLogger()->alert("TIMERUI DISABLE, NO FIND ECONOMYAPI PLUGIN");
            return;
        }

        $this->getServer()->getPluginManager()->registerEvents($this, $this);
    }

    public function onUse(PlayerInteractEvent $event)
    {
        if ($event->getItem()->getId() === (int)$this->getConfig()->get("id")) {
            if (empty(self::$player[$event->getPlayer()->getName()])) {
                if (!isset(self::$cooldown[$event->getPlayer()->getName()])) {
                    $this->getScheduler()->scheduleRepeatingTask(new TimerTask($this->getConfig()->get("time"), $event->getPlayer()), 20);
                    self::$cooldown[$event->getPlayer()->getName()] = time() + (int)$this->getConfig()->get("cooldown");
                } else {
                    if (time() < self::$cooldown[$event->getPlayer()->getName()]) {
                        $time = self::$cooldown[$event->getPlayer()->getName()] - time();
                        $event->getPlayer()->sendPopup(str_replace("{time}", $time, $this->getConfig()->get("time_")));
                    } else unset(self::$cooldown[$event->getPlayer()->getName()]);
                }
            } else {
                $form = new SimpleForm(function (Player $player, int $data = null) {
                    Server::getInstance()->getPluginManager()->getPlugin("EconomyAPI")->addMoney($player, self::$player[$player->getName()]);
                    unset(self::$player[$player->getName()]);
                });
                $form->setTitle(Timer::getInstance()->getConfig()->get("title"));
                $form->setContent(str_replace("{money}", self::$player[$event->getPlayer()->getName()], Timer::getInstance()->getConfig()->get("content")));
                $form->addButton(Timer::getInstance()->getConfig()->get("button"));
                $form->sendToPlayer($event->getPlayer());
            }
        }
    }

    public static function getInstance(): Timer
    {
        return self::$main;
    }
}