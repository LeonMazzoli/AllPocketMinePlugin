<?php

namespace HealStick;

use pocketmine\plugin\PluginBase;
use pocketmine\Server;

class HealMain extends PluginBase
{
    private static HealMain $main;

    public function onEnable(): void
    {
        self::$main = $this;
        $this->saveDefaultConfig();
        Server::getInstance()->getPluginManager()->registerEvents(new HealStickItem(), $this);
    }

    public static function getInstance(): HealMain
    {
        return self::$main;
    }
}