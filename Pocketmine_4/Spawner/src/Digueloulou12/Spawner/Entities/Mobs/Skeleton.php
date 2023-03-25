<?php

namespace Digueloulou12\Spawner\Entities\Mobs;

use Digueloulou12\Spawner\Entities\MobEntity;
use pocketmine\data\bedrock\EntityLegacyIds;
use pocketmine\item\ItemFactory;
use pocketmine\item\ItemIds;

class Skeleton extends MobEntity
{
    const TYPE_ID = EntityLegacyIds::SKELETON;

    public function getDrops(): array
    {
        return [
            ItemFactory::getInstance()->get(ItemIds::ARROW, 0, mt_rand(0, 2)),
            ItemFactory::getInstance()->get(ItemIds::BONE, 0, mt_rand(0, 2)),
        ];
    }

    public function getXpDropAmount(): int
    {
        return 5 + mt_rand(1, 3);
    }
}