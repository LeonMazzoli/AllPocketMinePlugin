<?php

namespace Digueloulou12\Spawner\Entities\Mobs;

use Digueloulou12\Spawner\Entities\MobEntity;
use pocketmine\data\bedrock\EntityLegacyIds;
use pocketmine\item\ItemFactory;
use pocketmine\item\ItemIds;

class Zombie extends MobEntity
{
    const TYPE_ID = EntityLegacyIds::ZOMBIE;

    public function getDrops(): array
    {
        $drops = [
            ItemFactory::getInstance()->get(ItemIds::ROTTEN_FLESH, 0, mt_rand(0, 2))
        ];

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