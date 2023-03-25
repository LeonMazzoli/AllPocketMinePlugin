<?php

namespace Digueloulou12;

use pocketmine\event\player\PlayerInteractEvent;
use Digueloulou12\Command\AnvilCommand;
use Digueloulou12\Forms\AnvilForms;
use pocketmine\plugin\PluginBase;
use pocketmine\event\Listener;

class AnvilMain extends PluginBase implements Listener
{
    private static AnvilMain $main;
    public function onEnable(): void
    {
        self::$main = $this;
        $this->getServer()->getPluginManager()->registerEvents($this, $this);
        if ($this->getConfig()->get("command_")) $this->getServer()->getCommandMap()->register("", new AnvilCommand());
    }

    public static function getInstance(): AnvilMain
    {
        return self::$main;
    }

    public function onUse(PlayerInteractEvent $event)
    {
        if ($event->getBlock()->getId() === 145) {
            if ($event->getPlayer()->getInventory()->getItemInHand()->getId() !== 0) {
                AnvilForms::mainForm($event->getPlayer());
            } else $event->getPlayer()->sendMessage($this->getConfig()->get("no_item"));
            $event->cancel();
        }
    }
}