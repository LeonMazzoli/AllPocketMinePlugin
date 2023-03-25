<?php

namespace Digueloulou12;

use pocketmine\event\player\PlayerMoveEvent;
use pocketmine\plugin\PluginBase;
use pocketmine\event\Listener;
use pocketmine\math\Vector3;
use pocketmine\utils\Config;

class BorderLimit extends PluginBase implements Listener
{
    public Config $config;

    public function onEnable(): void
    {
        $this->config = new Config($this->getDataFolder() . "config.yml", Config::YAML, [
            "min_x" => -500,
            "max_x" => 500,
            "min_z" => -500,
            "max_z" => 500,
            "msg" => true,
            "msg_" => "You have reached the limit!"
        ]);

        $this->getServer()->getPluginManager()->registerEvents($this, $this);
    }

    public function onMove(PlayerMoveEvent $event)
    {
        $x = $event->getPlayer()->getPosition()->x;
        $z = $event->getPlayer()->getPosition()->z;

        $motion = null;
        $config = $this->config;

        if ($x <= $config->get("min_x")) {
            $motion = new Vector3(+2, 1, 0);
        } elseif ($x >= $config->get("max_x")) {
            $motion = new Vector3(-2, 1, 0);
        } elseif ($z <= $config->get("min_z")) {
            $motion = new Vector3(0, 1, +2);
        } elseif ($z >= $config->get("max_z")) $motion = new Vector3(0, 1, -2);

        if ($motion !== null) {
            $event->getPlayer()->setMotion($motion);
            if ($this->config->get("msg")) $event->getPlayer()->sendMessage($this->config->get("msg_"));
        }
    }
}