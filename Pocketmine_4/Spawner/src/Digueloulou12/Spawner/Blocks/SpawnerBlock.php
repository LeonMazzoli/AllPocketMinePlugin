<?php

namespace Digueloulou12\Spawner\Blocks;

use Digueloulou12\Spawner\Entities\MobEntity;
use Digueloulou12\Spawner\Entities\Mobs\{Cow, Creeper, EnderMan, Pig, Skeleton, Spider, Witch, Zombie};
use Digueloulou12\Spawner\Tiles\SpawnerTile;
use Digueloulou12\Spawner\Utils\Utils;
use pocketmine\block\Block;
use pocketmine\block\MonsterSpawner;
use pocketmine\block\VanillaBlocks;
use pocketmine\data\bedrock\EntityLegacyIds;
use pocketmine\entity\Location;
use pocketmine\item\Item;
use pocketmine\item\SpawnEgg;
use pocketmine\math\Vector3;
use pocketmine\network\mcpe\protocol\types\entity\EntityIds;
use pocketmine\player\Player;
use pocketmine\world\BlockTransaction;
use pocketmine\world\Position;

class SpawnerBlock extends MonsterSpawner
{
    private static array $time = [];

    public function getDropsForCompatibleTool(Item $item): array
    {
        if (Utils::getConfigValue("get_spawner")) {
            $spawner = VanillaBlocks::MONSTER_SPAWNER()->asItem();
            $spawner->getNamedTag()->setString("spawner", $this->getEntityId());
            $spawner->setLore([$this->getEntityId()]);
            return [$spawner];
        } else return [];
    }

    public function getXpDropAmount(): int
    {
        return Utils::getConfigValue("get_spawner") ? 0 : mt_rand(15, 43);
    }

    public function place(BlockTransaction $tx, Item $item, Block $blockReplace, Block $blockClicked, int $face, Vector3 $clickVector, ?Player $player = null): bool
    {
        $this->writeStateToWorld();
        parent::place($tx, $item, $blockReplace, $blockClicked, $face, $clickVector, $player);
        if (!is_null($item->getNamedTag()->getTag("spawner"))) {
            $tag = $item->getNamedTag()->getString("spawner");
            $spawner = $this->getPosition()->getWorld()->getTile($this->getPosition());
            if ($spawner instanceof SpawnerTile) {
                $spawner->setEntityId($tag);
            }
        } return true;
    }

    public function onInteract(Item $item, int $face, Vector3 $clickVector, ?Player $player = null): bool
    {
        if ($player instanceof Player) {
            if ($item instanceof SpawnEgg) {
                $location = new Location(0, 0, 0, $this->getPosition()->getWorld(), 0, 0);
                $mob = $this->getMobIdInt($location, $item->getMeta());
                if (!is_null($mob)) {
                    $tile = $this->getPosition()->getWorld()->getTile($this->getPosition());
                    if ($tile instanceof SpawnerTile) {
                        if ($this->getEntityId() === ":") {
                            $tile->setEntityId($mob::getNetworkTypeId());
                            $player->getInventory()->setItemInHand($item->setCount($item->getCount() - 1));
                            $player->sendMessage(Utils::getConfigReplace("add_egg"));
                        } else $player->sendMessage(Utils::getConfigReplace("spawner_full"));
                    }
                }
            } else {
                $time = (self::$time[self::getStringByPosition($this->getPosition())] ?? 0) - time();
                $player->sendTip(Utils::getConfigReplace("spawner_info", ["{type}", "{time}"], [$this->getEntityId(), $time]));
            }
        }
        return true;
    }

    public function onScheduledUpdate(): void
    {
        $this->getPosition()->getWorld()->scheduleDelayedBlockUpdate($this->getPosition(), Utils::getConfigValue("spawn_time") * 20);
        self::$time[self::getStringByPosition($this->getPosition())] = time() + Utils::getConfigValue("spawn_time");

        if ($this->canUpdate()) {
            $pos = $this->getPosition()->add(mt_rand(-1, 1), mt_rand(-1, 1), mt_rand(-1, 1));
            $target = $this->getPosition()->getWorld()->getBlock($pos);
            if ($target->getId() == 0) {
                $entity = $this->getMobByIdString(new Location($pos->getX(), $pos->getY(), $pos->getZ(), $this->getPosition()->getWorld(), 0, 0));
                if ($entity instanceof MobEntity) $entity->spawnToAll();
            }
        }
    }

    public function canUpdate(): bool
    {
        if (!$this->getPosition()->getWorld()->isChunkLoaded($this->getPosition()->getX() >> 4, $this->getPosition()->getZ() >> 4)) return false;
        if ($this->getEntityId() === ":") return false;
        return true;
    }

    public function getEntityId(): string
    {
        $tile = $this->getPosition()->getWorld()->getTile($this->getPosition());
        if ($tile instanceof SpawnerTile) {
            return $tile->getEntityId();
        } else return ":";
    }

    public function getMobByIdString(Location $location): ?MobEntity
    {
        return match ($this->getEntityId()) {
            EntityIds::COW => new Cow($location),
            EntityIds::CREEPER => new Creeper($location),
            EntityIds::ENDERMAN => new EnderMan($location),
            EntityIds::PIG => new Pig($location),
            EntityIds::SKELETON => new Skeleton($location),
            EntityIds::SPIDER => new Spider($location),
            EntityIds::ZOMBIE => new Zombie($location),
            EntityIds::WITCH => new Witch($location),
            default => null
        };
    }

    public function getMobIdInt(Location $location, int $id): ?MobEntity
    {
        return match ($id) {
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

    public static function getStringByPosition(Position $position): string
    {
        return "{$position->x}!{$position->y}!{$position->z}!{$position->getWorld()->getFolderName()}";
    }
}