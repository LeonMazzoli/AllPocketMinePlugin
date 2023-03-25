<?php

namespace Toggle;

use pocketmine\Player;
use pocketmine\scheduler\Task;

class TaskSprint extends Task{
    private $player;
    private $time = 99999999999;

    public function __construct(Player $player) {
        $this->player = $player;
    }

    public function onRun(int $currentTick)
    {
        $player = $this->player;
        $player->setSprinting(true);
        $this->time--;
    }
}