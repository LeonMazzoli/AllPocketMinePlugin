<?php

namespace Digueloulou12\Events;

use Digueloulou12\Reward;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerJoinEvent;

class RewardEvents implements Listener
{
    public function onJoin(PlayerJoinEvent $event)
    {
        $config = Reward::$data;
        $name = $event->getPlayer()->getName();
        if ($config->exists($name)) {
            if ($config->get($name)["max_day"] === ($config->get($name)["day"] + 1)) {
                $config->set($name, ["day" => $config->get($name)["day"] + 1, "max_day" => $config->get($name)["max_day"], "loot" => false]);
            } else if ($config->get($name)["max_day"] !== ($config->get($name)["day"])) $config->set($name, ["day" => 1, "max_day" => 1, "loot" => false]);
        } else $config->set($name, ["day" => 1, "max_day" => 1, "loot" => false]);
        $config->save();
    }
}