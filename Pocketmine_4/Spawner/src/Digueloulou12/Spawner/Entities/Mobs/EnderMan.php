<?php

namespace Digueloulou12\Spawner\Entities\Mobs;

use Digueloulou12\Spawner\Entities\MobEntity;
use pocketmine\data\bedrock\EntityLegacyIds;
use pocketmine\item\ItemFactory;
use pocketmine\item\ItemIds;

class EnderMan extends MobEntity
{
    const TYPE_ID = EntityLegacyIds::ENDERMAN;

    public function getDrops(): array
    {
        return [
            ItemFactory::getInstance()->get(ItemIds::ENDER_PEARL, 0, mt_rand(0, 1)),
        ];
    }

    public function getXpDropAmount(): int
    {
        return 5 + mt_rand(1, 3);
    }
}