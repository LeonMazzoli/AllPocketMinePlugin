<?php

namespace Digueloulou12;

use pocketmine\data\bedrock\EntityLegacyIds;
use pocketmine\entity\projectile\Throwable;
use pocketmine\entity\EntityDataHelper;
use pocketmine\entity\EntityFactory;
use pocketmine\item\ItemIdentifier;
use pocketmine\item\ProjectileItem;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\plugin\PluginBase;
use pocketmine\item\ItemFactory;
use pocketmine\entity\Location;
use pocketmine\player\Player;
use pocketmine\item\ItemIds;
use pocketmine\world\World;

class EnderPearlMain extends PluginBase
{
    private static self $this;

    public function onEnable(): void
    {
        self::$this = $this;
        $this->saveDefaultConfig();

        ItemFactory::getInstance()->register(new EnderPearlItem(new ItemIdentifier(ItemIds::ENDER_PEARL, 0), "EnderPearl"), true);

        EntityFactory::getInstance()->register(EnderPearlEntity::class, function (World $world, CompoundTag $nbt): EnderPearlEntity {
            return new EnderPearlEntity(EntityDataHelper::parseLocation($nbt, $world), null, $nbt);
        }, ['ThrownEnderpearl', 'minecraft:ender_pearl'], EntityLegacyIds::ENDER_PEARL);
    }

    public static function getInstance(): self
    {
        return self::$this;
    }
}