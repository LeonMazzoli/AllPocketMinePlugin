<?php

namespace Assassin;

use Assassin\Command\Shop;
use pocketmine\plugin\PluginBase;
use pocketmine\Server;

class ShopMain extends PluginBase{
    public function onEnable()
    {
        $this->getLogger()->info("ShopAssassin on by Digueloulou12");
        $this->getServer()->getCommandMap()->register("shop", new Shop($this));
    }

    public function onDisable()
    {
        $this->getLogger()->info("ShopAssassin off by Digueloulou12");
        foreach (Server::getInstance()->getOnlinePlayers() as $player){
            $player->transfer("45.140.165.216", 19132);
        }
    }
}