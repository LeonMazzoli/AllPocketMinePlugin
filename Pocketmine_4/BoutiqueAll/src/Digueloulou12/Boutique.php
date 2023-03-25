<?php

namespace Digueloulou12;

use Digueloulou12\Command\BoutiqueCommand;
use pocketmine\plugin\PluginBase;

class Boutique extends PluginBase
{
    private static Boutique $main;

    public function onEnable(): void
    {
        self::$main = $this;
        $this->saveDefaultConfig();

        if ($this->getServer()->getPluginManager()->getPlugin("Token") === null) {
            $this->getLogger()->alert("BOUTIQUE PLUGIN IS DISABLE, NOT FOUND PLUGIN TOKEN");
            $this->getServer()->getPluginManager()->disablePlugin($this);
            return;
        }

        $this->getServer()->getCommandMap()->register("", new BoutiqueCommand());
    }

    public static function getMain(): Boutique
    {
        return self::$main;
    }
}