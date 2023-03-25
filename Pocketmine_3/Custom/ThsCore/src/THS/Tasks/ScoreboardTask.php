<?php

namespace THS\Tasks;

use pocketmine\Player;
use pocketmine\scheduler\Task;
use THS\API\LoadAPI;

class ScoreboardTask extends Task{
    private $player;

    public function __construct($player)
    {
        $this->player = $player;
    }

    public function onRun(int $tick): void
    {
        if (!($this->player instanceof Player)) {
            $this->getHandler()->cancel();
            return;
        }

        LoadAPI::getScoreboard()->updateScoreboard($this->player);
    }
}