<?php

namespace Digueloulou12\LobbyCore\Tasks;

use Digueloulou12\LobbyCore\Utils\Utils;
use pocketmine\scheduler\Task;
use pocketmine\Server;

class SentencesTask extends Task
{
    public function onRun(): void
    {
        Server::getInstance()->broadcastMessage(Utils::getConfigValue("sentences")[array_rand(Utils::getConfigValue("sentences"))]);
    }
}