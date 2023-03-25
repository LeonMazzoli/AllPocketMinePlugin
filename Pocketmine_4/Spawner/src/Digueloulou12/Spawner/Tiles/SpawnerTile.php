<?php

namespace Digueloulou12\Spawner\Tiles;

use pocketmine\block\tile\MonsterSpawner;
use pocketmine\data\bedrock\LegacyEntityIdToStringIdMap;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\nbt\tag\IntTag;
use pocketmine\nbt\tag\StringTag;

class SpawnerTile extends MonsterSpawner
{
    private const TAG_LEGACY_ENTITY_TYPE_ID = "EntityId";
    private const TAG_ENTITY_TYPE_ID = "EntityIdentifier";
    public string $entityId = ":";

    public function getEntityId(): string
    {
        return $this->entityId;
    }

    public function setEntityId(string $id): void
    {
        $this->entityId = $id;
    }

    public function readSaveData(CompoundTag $nbt): void
    {
        parent::readSaveData($nbt);
        if (($legacyIdTag = $nbt->getTag(self::TAG_LEGACY_ENTITY_TYPE_ID)) instanceof IntTag) {
            $this->entityId = LegacyEntityIdToStringIdMap::getInstance()->legacyToString($legacyIdTag->getValue()) ?? ":";
        } elseif (($idTag = $nbt->getTag(self::TAG_ENTITY_TYPE_ID)) instanceof StringTag) {
            $this->entityId = $idTag->getValue();
        } else $this->entityId = ":";
    }

    protected function writeSaveData(CompoundTag $nbt): void
    {
        parent::writeSaveData($nbt);
        $nbt->setString(self::TAG_ENTITY_TYPE_ID, $this->entityId);
    }

    protected function addAdditionalSpawnData(CompoundTag $nbt): void
    {
        parent::addAdditionalSpawnData($nbt);
        $nbt->setString(self::TAG_ENTITY_TYPE_ID, $this->entityId);
    }
}