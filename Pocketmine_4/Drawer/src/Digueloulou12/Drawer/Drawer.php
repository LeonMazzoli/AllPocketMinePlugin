<?php

namespace Digueloulou12\Drawer;

use Digueloulou12\Drawer\API\DrawerAPI;
use Digueloulou12\Drawer\Events\DrawerEvents;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\Config;

class Drawer extends PluginBase
{
    private static self $main;
    private DrawerAPI $API;

    public function onEnable(): void
    {
        self::$main = $this;
        $this->saveDefaultConfig();
        $this->API = new DrawerAPI();
        $this->API->init(new Config($this->getDataFolder() . "DrawerData.json", Config::JSON));
        $this->getServer()->getPluginManager()->registerEvents(new DrawerEvents(), $this);
    }

    public function getAPI(): DrawerAPI
    {
        return $this->API;
    }

    public function onDisable(): void
    {
        $this->API->save();
    }

    public static function getInstance(): self
    {
        return self::$main;
    }
}