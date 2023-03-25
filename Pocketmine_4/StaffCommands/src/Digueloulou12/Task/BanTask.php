<?php

namespace Digueloulou12\Task;

use pocketmine\scheduler\Task;
use pocketmine\player\Player;
use Digueloulou12\Staff;

class BanTask extends Task
{
    public Player $player;
    public int $time = 1;

    public function __construct(Player $player)
    {
        $this->player = $player;
        Staff::getInstance()->getScheduler()->scheduleDelayedRepeatingTask($this, 20, 20);
    }

    public function onRun(): void
    {
        $player = $this->player;
        if (!$player->isOnline()) {
            $this->getHandler()->cancel();
            return;
        }

        $this->time--;

        if ($this->time === 0) {
            $reason = Staff::getInstance()->getReasonBan($player);
            $staff = Staff::getInstance()->getStaffBan($player);

            $time = Staff::getInstance()->getTimeBan($player);
            $day = Staff::getInstance()->getRemainingTime($time, "day");
            $hour = Staff::getInstance()->getRemainingTime($time, "hour");
            $min = Staff::getInstance()->getRemainingTime($time, "minute");
            $second = Staff::getInstance()->getRemainingTime($time, "second");

            $player->kick(Staff::getInstance()->getConfigReplace("ban_join", ["{reason}", "{staff}", "{day}", "{hour}", "{minute}", "{second}"], [$reason, $staff, $day, $hour, $min, $second]));
            $this->getHandler()->cancel();
        }
    }
}