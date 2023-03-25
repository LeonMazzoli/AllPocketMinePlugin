<?php

namespace Digueloulou12;

use pocketmine\console\ConsoleCommandSender;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerItemUseEvent;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\Config;

class ItemCommands extends PluginBase implements Listener
{
    public function onEnable(): void
    {
        if (!file_exists($this->getDataFolder() . "config.yml")) {
            new Config($this->getDataFolder() . "config.yml", Config::YAML, [
                "369-0" => ["server:say The player {player} use BlazeRod !", "server:tell {player} Hey !"],
                "280-0" => ["player:ver"]
            ]);
        } else $this->saveDefaultConfig();
        $this->getServer()->getPluginManager()->registerEvents($this, $this);
    }

    public function onUse(PlayerItemUseEvent $event)
    {
        $config = $this->getConfig();
        $id = $event->getItem()->getId() . "-" . $event->getItem()->getMeta();
        if ($config->exists($id)) {
            foreach ($config->get($id) as $cmd) {
                if (explode(":", $cmd)[0] === "server") {
                    $this->getServer()->getCommandMap()->dispatch(new ConsoleCommandSender($this->getServer(), $this->getServer()->getLanguage()), str_replace("{player}", $event->getPlayer()->getName(), explode(":", $cmd)[1]));
                } else $this->getServer()->getCommandMap()->dispatch($event->getPlayer(), explode(":", $cmd)[1]);
            }
        }
    }
}