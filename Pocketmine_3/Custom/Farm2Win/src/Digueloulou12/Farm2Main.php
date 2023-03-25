<?php

namespace Digueloulou12;

use pocketmine\plugin\PluginBase;
use pocketmine\Server;

class Farm2Main extends PluginBase{
    public function onEnable()
    {
        $this->saveDefaultConfig();

        if (Server::getInstance()->getPluginManager()->getPlugin("EconomyAPI") === null){
            Server::getInstance()->getPluginManager()->disablePlugin($this);
            $this->getLogger()->alert("LE PLUGIN A ETE DESACTIVE PARCE QUE LE PLUGIN ECONOMY_API N'EST PAS SUR LE SERVEUR !!!");
            return;
        }

        Server::getInstance()->getCommandMap()->register("f2w", new Farm2Command($this));
    }
}