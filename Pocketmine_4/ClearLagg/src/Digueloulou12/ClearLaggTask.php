<?php

namespace Digueloulou12;

use pocketmine\entity\object\ExperienceOrb;
use pocketmine\entity\object\ItemEntity;
use pocketmine\scheduler\Task;
use pocketmine\Server;

class ClearLaggTask extends Task
{
    private int $time;

    public function __construct(int $time = 300)
    {
        $this->time = $time;
    }

    public function onRun(): void
    {
        $config = ClearLagg::getInstance()->getConfig();

        if ($this->time == 0) {
            $count = 0;
            foreach (Server::getInstance()->getWorldManager()->getWorlds() as $world) {
                foreach ($world->getEntities() as $entity) {
                    if (($entity instanceof ItemEntity) or ($entity instanceof ExperienceOrb)) {
                        $entity->flagForDespawn();
                        $count++;
                    }
                }
            }
            Server::getInstance()->broadcastMessage(str_replace("{count}", $count, $config->get("msg_")));
            $this->time = $config->get("time");
        } else {
            if (in_array($this->time, $config->get("time_chat"))) {
                Server::getInstance()->broadcastMessage(str_replace("{time}", $this->time, $config->get("msg")));
            }
        }
        $this->time--;
    }
}