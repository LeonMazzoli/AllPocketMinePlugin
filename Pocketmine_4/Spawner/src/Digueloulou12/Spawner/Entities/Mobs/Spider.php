<?php

namespace Digueloulou12\Spawner\Entities\Mobs;

use Digueloulou12\Spawner\Entities\MobEntity;
use pocketmine\data\bedrock\EntityLegacyIds;
use pocketmine\item\ItemFactory;
use pocketmine\item\ItemIds;

class Spider extends MobEntity
{
    const TYPE_ID = EntityLegacyIds::SPIDER;

    public function getMaxHealth(): int
    {
        return 16;
    }

    public function getDrops(): array
    {
        $drops = [ItemFactory::getInstance()->get(ItemIds::STRING, 0, 1)];
        if (mt_rand(0, 199) < 5) {
            switch (mt_rand(0, 2)) {
                case 0:
                    $drops[] = ItemFactory::getInstance()->get(ItemIds::IRON_INGOT, 0, 1);
                    break;
                case 1:
                    $drops[] = ItemFactory::getInstance()->get(ItemIds::CARROT, 0, 1);
                    break;
                case 2:
                    $drops[] = ItemFactory::getInstance()->get(ItemIds::POTATO, 0, 1);
                    break;
            }
        }

        return $drops;
    }

    public function getXpDropAmount(): int
    {
        return 5 + mt_rand(1, 3);
    }
}