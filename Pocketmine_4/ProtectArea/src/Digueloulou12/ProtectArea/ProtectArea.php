<?php

namespace Digueloulou12\ProtectArea;

use Digueloulou12\ProtectArea\API\ProtectAreaAPI;
use Digueloulou12\ProtectArea\Commands\ProtectAreaCommand;
use Digueloulou12\ProtectArea\Events\BlockEvents;
use Digueloulou12\ProtectArea\Events\EntityEvents;
use Digueloulou12\ProtectArea\Events\PlayerEvents;
use Digueloulou12\ProtectArea\Utils\Utils;
use JsonException;
use pocketmine\plugin\PluginBase;

class ProtectArea extends PluginBase
{
    private ProtectAreaAPI $protectAreaAPI;
    private static self $this;

    public function onEnable(): void
    {
        self::$this = $this;
        $this->protectAreaAPI = new ProtectAreaAPI();

        foreach (
            [
                new BlockEvents(),
                new PlayerEvents(),
                new EntityEvents()
            ] as $event) {
            $this->getServer()->getPluginManager()->registerEvents($event, $this);
        }

        $this->getServer()->getCommandMap()->register("ProtectAreaCommand",
            new ProtectAreaCommand(
                Utils::getConfigValue("command")[0] ?? "protectarea",
                Utils::getConfigValue("command")[1] ?? "ProtectArea Command",
                Utils::getConfigValue("commandAliases") ?? [],
            )
        );
    }

    /**
     * @throws JsonException
     */
    public function onDisable(): void
    {
        $this->protectAreaAPI->save();
    }

    public function getProtectAreaAPI(): ProtectAreaAPI
    {
        return $this->protectAreaAPI;
    }

    public static function getInstance(): self
    {
        return self::$this;
    }
}