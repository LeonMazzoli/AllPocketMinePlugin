<?php

namespace Digueloulou12\Tasks;

use pocketmine\Player;
use pocketmine\Server;
use pocketmine\math\Vector3;
use pocketmine\scheduler\Task;
use Digueloulou12\API\FactionAPI;
use pocketmine\level\particle\DustParticle;

class ChunkTask extends Task
{
    public function onRun(int $currentTick)
    {
        if (empty(FactionAPI::$chunk)) return;
        foreach (FactionAPI::$chunk as $player => $name) {
            $sender = Server::getInstance()->getPlayer($player);
            if (!($sender instanceof Player)) {
                unset(FactionAPI::$chunk[$player]);
                return;
            }

            $minX = (float)$sender->getLevel()->getChunkAtPosition($sender)->getX() * 16;
            $maxX = (float)$minX + 16;
            $minZ = (float)$sender->getLevel()->getChunkAtPosition($sender)->getZ() * 16;
            $maxZ = (float)$minZ + 16;

            for ($x = $minX; $x <= $maxX; $x += 0.5) {
                for ($z = $minZ; $z <= $maxZ; $z += 0.5) {
                    if ($x === $minX || $x === $maxX || $z === $minZ || $z === $maxZ) {
                        if (FactionAPI::isChunkClaim($sender->getLevel()->getChunkAtPosition($sender))) {
                            $sender->getLevel()->addParticle(new DustParticle(new Vector3($x, $sender->getY() + 1.5, $z), 204, 204, 0), [$sender]);
                        } else $sender->getLevel()->addParticle(new DustParticle(new Vector3($x, $sender->getY() + 1.5, $z), 0, 102, 204), [$sender]);
                    }
                }
            }
        }
    }
}