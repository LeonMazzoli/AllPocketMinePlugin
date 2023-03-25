<?php

namespace Digueloulou12\Spawner\Entities;

use JetBrains\PhpStorm\Pure;
use pocketmine\data\bedrock\LegacyEntityIdToStringIdMap;
use pocketmine\entity\EntitySizeInfo;
use pocketmine\entity\Living;

class MobEntity extends Living
{
    #[Pure] protected function getInitialSizeInfo(): EntitySizeInfo
    {
        return new EntitySizeInfo(1.8, 0.6);
    }

    public static function getNetworkTypeId(): string
    {
        return LegacyEntityIdToStringIdMap::getInstance()->legacyToString(static::TYPE_ID);
    }

    public function getName(): string
    {
        $data = explode("\\", get_class($this));
        return end($data);
    }

    public function canSaveWithChunk(): bool
    {
        return false;
    }
}