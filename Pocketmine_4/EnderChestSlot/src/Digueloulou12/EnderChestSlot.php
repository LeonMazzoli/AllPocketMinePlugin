<?php

namespace Digueloulou12;

use Digueloulou12\Events\EnderChestEvents;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\Config;

class EnderChestSlot extends PluginBase
{
    public static Config $config;

    public function onEnable(): void
    {
        if (!file_exists($this->getDataFolder() . "config.yml")) {
            self::$config = new Config($this->getDataFolder() . "config.yml", Config::YAML, [
                "# PERMISSION: SLOT",
                "slot_default" => "2",
                "slots" => [
                    "vip.slot" => 10,
                    "player.slot" => 4
                ],
                "item" => [160, 14, "Custom Name"]
            ]);
        } else self::$config = $this->getConfig();

        $this->getServer()->getPluginManager()->registerEvents(new EnderChestEvents(), $this);
    }
}