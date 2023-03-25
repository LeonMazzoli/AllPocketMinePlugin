<?php

namespace Digueloulou12;

use Digueloulou12\Command\RandomTpCommand;
use pocketmine\plugin\PluginBase;

class RandomTp extends PluginBase
{
    private static RandomTp $main;

    public function onEnable(): void
    {
        self::$main = $this;
        $this->saveDefaultConfig();
        $this->getServer()->getCommandMap()->register("", new RandomTpCommand());
    }

    public static function getInstance(): RandomTp
    {
        return self::$main;
    }
}