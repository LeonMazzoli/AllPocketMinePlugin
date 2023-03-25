<?php

namespace Digueloulou12;

use pocketmine\plugin\PluginBase;

class LobbyMain extends PluginBase{
    public function onEnable()
    {
        $this->getServer()->getPluginManager()->registerEvents(new InteractEvent(), $this);
        $this->getServer()->getPluginManager()->registerEvents(new JoinEvent(), $this);
    }
}