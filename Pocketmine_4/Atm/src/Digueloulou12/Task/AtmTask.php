<?php

namespace Digueloulou12\Task;

use Digueloulou12\Atm;
use pocketmine\scheduler\Task;
use pocketmine\player\Player;

class AtmTask extends Task
{
    private Player $player;
    private int $money;

    public function __construct(Player $player, int $money)
    {
        $this->player = $player;
        $this->money = $money;
    }

    public function onRun(): void
    {
        $player = $this->player;
        if ($player->isOnline()) {
            Atm::getAtm()->getAtmData()->set($player->getName(), Atm::getAtm()->getAtmData()->get($player->getName()) + $this->money);
        } else $this->getHandler()->cancel();
    }
}