<?php

namespace Digueloulou12;

use Digueloulou12\Commands\EffectCommand;
use pocketmine\plugin\PluginBase;

class CommandEffects extends PluginBase
{
    public function onEnable(): void
    {
        $this->saveDefaultConfig();

        foreach ($this->getConfig()->getAll() as $command => $key) {
            $this->getServer()->getCommandMap()->register("EffectCommand", new EffectCommand($command, $key["description"], $key["aliases"], $key["effects"], $key["permission"] ?? null));
        }
    }
}