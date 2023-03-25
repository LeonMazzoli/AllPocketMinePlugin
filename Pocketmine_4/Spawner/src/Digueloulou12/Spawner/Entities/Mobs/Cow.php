<?php

namespace Digueloulou12\Spawner\Entities\Mobs;

use Digueloulou12\Spawner\Entities\MobEntity;
use pocketmine\data\bedrock\EntityLegacyIds;
use pocketmine\item\ItemFactory;
use pocketmine\item\ItemIds;

class Cow extends MobEntity
{
    const TYPE_ID = EntityLegacyIds::COW;

    public function getMaxHealth(): int
    {
        return 10;
    }

    public function getDrops(): array
    {
        return [
            ItemFactory::getInstance()->get(ItemIds::RAW_BEEF, 0, mt_rand(1, 3)),
            ItemFactory::getInstance()->get(ItemIds::LEATHER, 0, mt_rand(0, 2)),
        ];
    }

    public function getXpDropAmount(): int
    {
        return mt_rand(1, 3);
    }
}