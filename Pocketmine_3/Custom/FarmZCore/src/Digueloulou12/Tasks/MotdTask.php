<?php

namespace Digueloulou12\Tasks;

use Digueloulou12\Main;
use pocketmine\scheduler\Task;
use pocketmine\Server;

class MotdTask extends Task
{
    private int $line = -1;

    public function onRun(int $tick): void
    {
        $motd = Main::getConfigAPI()->getConfigValue("motd");
        $this->line++;
        Server::getInstance()->getNetwork()->setName($motd[$this->line]);
        if ($this->line === count($motd) - 1) $this->line = -1;
    }
}