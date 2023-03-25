<?php

namespace Digueloulou12\Spawner\Entities\Mobs;

use Digueloulou12\Spawner\Entities\MobEntity;
use pocketmine\data\bedrock\EntityLegacyIds;
use pocketmine\item\ItemFactory;
use pocketmine\item\ItemIds;

class Pig extends MobEntity
{
    const TYPE_ID = EntityLegacyIds::PIG;

    public function getMaxHealth(): int
    {
        return 10;
    }

    public function getDrops(): array
    {
        return [
            ItemFactory::getInstance()->get(ItemIds::RAW_PORKCHOP, 0, mt_rand(1, 3)),
        ];
    }

    public function getXpDropAmount(): int
    {
        return mt_rand(1, 3);
    }
}