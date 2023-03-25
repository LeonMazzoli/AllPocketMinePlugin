<?php

namespace Digueloulou12\Spawner\Entities\Mobs;

use Digueloulou12\Spawner\Entities\MobEntity;
use pocketmine\data\bedrock\EntityLegacyIds;
use pocketmine\entity\EntitySizeInfo;
use pocketmine\item\ItemFactory;
use pocketmine\item\ItemIds;
use pocketmine\network\mcpe\protocol\types\entity\EntityIds;

class Creeper extends MobEntity
{
    const TYPE_ID = EntityLegacyIds::CREEPER;

    public function getDrops(): array
    {
        if (mt_rand(1, 10) < 3) {
            return [ItemFactory::getInstance()->get(ItemIds::GUNPOWDER, 0, 1)];
        }

        return [];
    }

    public function getXpDropAmount(): int
    {
        return 5 + mt_rand(1, 3);
    }
}