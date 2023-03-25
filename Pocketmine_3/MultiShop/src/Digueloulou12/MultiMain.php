<?php

namespace Digueloulou12;

use Digueloulou12\Shop\ShopCommand;
use pocketmine\plugin\PluginBase;

class MultiMain extends PluginBase
{
    private static MultiMain $main;

    public function onEnable()
    {
        self::$main = $this;
        $this->saveResource("config.yml");
        $this->getServer()->getCommandMap()->register("", new ShopCommand());
    }

    public static function getInstance(): MultiMain
    {
        return self::$main;
    }
}