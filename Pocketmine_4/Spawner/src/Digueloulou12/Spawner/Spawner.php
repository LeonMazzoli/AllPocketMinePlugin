<?php

namespace Digueloulou12\Spawner;

use Digueloulou12\Spawner\Entities\Mobs\{Cow, Creeper, EnderMan, Pig, Skeleton, Spider, Witch, Zombie};
use Digueloulou12\Spawner\Blocks\SpawnerBlock;
use Digueloulou12\Spawner\Entities\MobEntity;
use Digueloulou12\Spawner\Items\SpawnEggItem;
use Digueloulou12\Spawner\Tiles\SpawnerTile;
use pocketmine\block\BlockBreakInfo;
use pocketmine\block\BlockFactory;
use pocketmine\block\BlockIdentifier;
use pocketmine\block\BlockLegacyIds;
use pocketmine\block\BlockToolType;
use pocketmine\block\tile\TileFactory;
use pocketmine\data\bedrock\EntityLegacyIds;
use pocketmine\entity\EntityDataHelper;
use pocketmine\entity\EntityFactory;
use pocketmine\entity\Living;
use pocketmine\entity\Location;
use pocketmine\inventory\CreativeInventory;
use pocketmine\item\ItemFactory;
use pocketmine\item\ItemIdentifier;
use pocketmine\item\ItemIds;
use pocketmine\item\ToolTier;
use pocketmine\math\Vector3;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\plugin\PluginBase;
use pocketmine\world\World;

class Spawner extends PluginBase
{
    private static self $this;

    public function onEnable(): void
    {
        self::$this = $this;
        $this->saveDefaultConfig();
        $entities = [
            "Cow" => Cow::class,
            "Creeper" => Creeper::class,
            "EnderMan" => EnderMan::class,
            "Pig" => Pig::class,
            "Skeleton" => Skeleton::class,
            "Spider" => Spider::class,
            "Witch" => Witch::class,
            "Zombie" => Zombie::class
        ];
        foreach ($entities as $entityName => $typeClass) {
            EntityFactory::getInstance()->register($typeClass, static function (World $world, CompoundTag $nbt) use ($entityName): Living {
                return new $entityName(EntityDataHelper::parseLocation($nbt, $world), $nbt);
            }, [$entityName], $typeClass::TYPE_ID);
        }

        $spawnEggs = [
            new SpawnEggItem(new ItemIdentifier(ItemIds::SPAWN_EGG, EntityLegacyIds::COW), "Cow Spawn Egg"),
            new SpawnEggItem(new ItemIdentifier(ItemIds::SPAWN_EGG, EntityLegacyIds::CREEPER), "Creeper Spawn Egg"),
            new SpawnEggItem(new ItemIdentifier(ItemIds::SPAWN_EGG, EntityLegacyIds::ENDERMAN), "Enderman Spawn Egg"),
            new SpawnEggItem(new ItemIdentifier(ItemIds::SPAWN_EGG, EntityLegacyIds::PIG), "Pig Spawn Egg"),
            new SpawnEggItem(new ItemIdentifier(ItemIds::SPAWN_EGG, EntityLegacyIds::SKELETON), "Skeleton Spawn Egg"),
            new SpawnEggItem(new ItemIdentifier(ItemIds::SPAWN_EGG, EntityLegacyIds::SPIDER), "Spider Spawn Egg"),
            new SpawnEggItem(new ItemIdentifier(ItemIds::SPAWN_EGG, EntityLegacyIds::WITCH), "Witch Spawn Egg")
        ];
        foreach ($spawnEggs as $spawnEgg) {
            ItemFactory::getInstance()->register($spawnEgg);
            CreativeInventory::getInstance()->add($spawnEgg);
        }

        ItemFactory::getInstance()->register(new SpawnEggItem(new ItemIdentifier(ItemIds::SPAWN_EGG, EntityLegacyIds::ZOMBIE), "Zombie Spawn Egg"), true);

        TileFactory::getInstance()->register(SpawnerTile::class);
        BlockFactory::getInstance()->register(
            new SpawnerBlock(
                new BlockIdentifier(BlockLegacyIds::MOB_SPAWNER, 0, null, SpawnerTile::class),
                "Monster Spawner",
                new BlockBreakInfo(5.0, BlockToolType::PICKAXE, ToolTier::WOOD()->getHarvestLevel())), true
        );
    }

    public function getSpawnEntity(World $world, Vector3 $pos, float $yaw, float $pitch, int $meta): ?MobEntity
    {
        $location = new Location($pos->getX(), $pos->getY(), $pos->getZ(), $world, $yaw, $pitch);
        return match ($meta) {
            EntityLegacyIds::COW => new Cow($location),
            EntityLegacyIds::CREEPER => new Creeper($location),
            EntityLegacyIds::ENDERMAN => new EnderMan($location),
            EntityLegacyIds::PIG => new Pig($location),
            EntityLegacyIds::SKELETON => new Skeleton($location),
            EntityLegacyIds::SPIDER => new Spider($location),
            EntityLegacyIds::ZOMBIE => new Zombie($location),
            EntityLegacyIds::WITCH => new Witch($location),
            default => null
        };
    }

    public static function getInstance(): self
    {
        return self::$this;
    }
}