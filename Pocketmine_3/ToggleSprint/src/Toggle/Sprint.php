<?php

namespace Toggle;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\event\Listener;
use pocketmine\Player;
use pocketmine\plugin\PluginBase;

class Sprint extends PluginBase implements Listener{
    private static $sprint = [];
    public function onEnable()
    {
        $this->getLogger()->info("ToggleSprint on by Digueloulou12");
        $this->saveResource('config.yml');
    }

    public function onDisable()
    {
        $this->getLogger()->info("ToggleSprint on by Digueloulou12");
    }

    public function onCommand(CommandSender $player, Command $command, string $label, array $args): bool
    {
        switch ($command->getName()) {
            case "togglesprint":
                if ($player instanceof Player) {
                    if (!empty(self::$sprint[$player->getName()])) {
                        $player->sendMessage($this->getConfig()->get("toggle.off"));
                        unset(self::$sprint[$player->getName()]);
                        $this->getScheduler()->cancelAllTasks();
                    } else {
                        $player->sendMessage($this->getConfig()->get("toggle.on"));
                        self::$sprint[$player->getName()] = $player;
                        $this->getScheduler()->scheduleRepeatingTask(new TaskSprint($player), 1);
                    }
                } else $player->sendMessage($this->getConfig()->get("toggle.console"));
                break;
        }
        return true;
    }
}