<?php

namespace Digueloulou12;

use jojoe77777\FormAPI\SimpleForm;
use pocketmine\scheduler\Task;
use pocketmine\Player;
use pocketmine\Server;

class TimerTask extends Task
{
    private int $time;
    private Player $player;

    public function __construct(int $time, Player $player)
    {
        $this->time = $time;
        $this->player = $player;
    }

    public function onRun(int $currentTick)
    {
        if ($this->time == 0) {
            $rand = mt_rand(Timer::getInstance()->getConfig()->get("min"), Timer::getInstance()->getConfig()->get("max"));
            Timer::$player[$this->player->getName()] = $rand;
            $this->player->sendMessage(Timer::getInstance()->getConfig()->get("msg"));
            $this->getHandler()->cancel();
        } else {
            if ($this->player instanceof Player) {
                $this->player->sendPopup(str_replace("{time}", $this->time, Timer::getInstance()->getConfig()->get("popup")));
            } else $this->getHandler()->cancel();
        }
        $this->time--;
    }
}