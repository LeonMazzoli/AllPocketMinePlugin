<?php

namespace Digueloulou12\Koth;

use Digueloulou12\Koth\Command\KothCommand;
use Digueloulou12\Koth\Utils\Utils;
use pocketmine\plugin\PluginBase;

class Koth extends PluginBase
{
    private static self $this;

    public function onEnable(): void
    {
        self::$this = $this;
        $this->saveDefaultConfig();

        $this->getServer()->getCommandMap()->register("KothCommand", new KothCommand(
            Utils::getConfigValue("koth")[0],
            Utils::getConfigValue("koth")[1] ?? "",
            Utils::getConfigValue("koth_aliases") ?? []
        ));
    }

    public static function getInstance(): self
    {
        return self::$this;
    }
}