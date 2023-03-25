<?php

namespace Digueloulou12\Spawner\Entities\Mobs;

use Digueloulou12\Spawner\Entities\MobEntity;
use pocketmine\data\bedrock\EntityLegacyIds;
use pocketmine\entity\EntitySizeInfo;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\network\mcpe\protocol\types\entity\EntityIds;

class Witch extends MobEntity
{
    const TYPE_ID = EntityLegacyIds::WITCH;

    public function getDrops(): array
    {
        return [];
    }

    public function getXpDropAmount(): int
    {
        return 5 + mt_rand(1, 3);
    }
}