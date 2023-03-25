<?php

namespace THS\Tasks;

use pocketmine\Player;
use pocketmine\scheduler\Task;

class ToggleSprintTask extends Task{
    private $player;
    public static $taskId;
    public function __construct(Player $player)
    {
        $this->player = $player;
        self::$taskId = $this->getTaskId();
    }

    public function onRun(int $currentTick)
    {
        $this->player->setSprinting(true);
    }
}