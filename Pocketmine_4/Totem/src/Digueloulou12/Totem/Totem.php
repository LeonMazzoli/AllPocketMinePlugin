<?php

namespace Digueloulou12\Totem;

use Digueloulou12\Totem\Commands\TotemCommand;
use Digueloulou12\Totem\Events\TotemEvents;
use Digueloulou12\Totem\Utils\Utils;
use pocketmine\plugin\PluginBase;

class Totem extends PluginBase
{
    private static self $this;

    public function onEnable(): void
    {
        self::$this = $this;
        $this->saveDefaultConfig();

        $this->getServer()->getCommandMap()->register("TotemCommand", new TotemCommand(
            Utils::getConfigValue("totem")[0],
            isset(Utils::getConfigValue("totem")[1]) ? Utils::getConfigValue("totem")[1] : "",
            Utils::getConfigValue("totem_aliases") ?? []
        ));

        $this->getServer()->getPluginManager()->registerEvents(new TotemEvents(), $this);
    }

    public static function getInstance(): self
    {
        return self::$this;
    }
}