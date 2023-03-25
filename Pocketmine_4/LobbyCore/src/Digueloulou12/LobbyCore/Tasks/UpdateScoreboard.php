<?php

namespace Digueloulou12\LobbyCore\Tasks;

use Digueloulou12\LobbyCore\API\ScoreboardAPI;
use pocketmine\scheduler\Task;

class UpdateScoreboard extends Task
{
    public function onRun(): void
    {
       ScoreboardAPI::updateScoreboard();
    }
}