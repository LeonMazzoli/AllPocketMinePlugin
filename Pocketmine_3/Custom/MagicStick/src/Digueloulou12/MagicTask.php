<?php

namespace Digueloulou12;

use pocketmine\block\BlockIds;
use pocketmine\level\Position;
use pocketmine\scheduler\Task;
use pocketmine\block\Cobweb;
use pocketmine\block\Block;
use pocketmine\Server;

class MagicTask extends Task
{
    private string $sender;
    private int $time;

    public function __construct(string $name, int $time = 5)
    {
        $this->time = $time;
        $this->sender = $name;
        MagicStick::getInstance()->getScheduler()->scheduleDelayedRepeatingTask($this, 20, 20);
    }

    public function onRun(int $currentTick)
    {
        if ($this->time == 0) {
            foreach (MagicStick::$blocks[$this->sender] as $block) {
                $pos = new Position($block[0], $block[1], $block[2], Server::getInstance()->getLevel($block[3]));
                if ($pos->getLevel()->getBlock($pos) instanceof Cobweb) {
                    $pos->getLevel()->setBlock($pos, Block::get(BlockIds::AIR), false, true);
                }
            }
            $this->getHandler()->cancel();
            return;
        }
        $this->time--;
    }
}