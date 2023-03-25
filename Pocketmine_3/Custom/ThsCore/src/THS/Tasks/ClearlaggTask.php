<?php

namespace THS\Tasks;

use pocketmine\entity\object\ItemEntity;
use pocketmine\scheduler\Task;
use pocketmine\Server;

class ClearlaggTask extends Task{
    private static $time = 60;

    public function onRun(int $currentTick)
    {
        $time = self::$time;
        if($time == 0){
            foreach(Server::getInstance()->getLevels() as $level){
                foreach($level->getEntities() as $entity){
                    if($entity instanceof ItemEntity){
                        $entity->flagForDespawn();
                    }
                }
            }
            self::$time = 30;
        }
        self::$time = self::$time -5;
    }
}