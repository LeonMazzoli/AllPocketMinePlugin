<?php

namespace Digueloulou12\UltraHardCore;

use Digueloulou12\UltraHardCore\API\UltraHardCoreAPI;
use Digueloulou12\UltraHardCore\Commands\UltraHardCoreCommand;
use Digueloulou12\UltraHardCore\Entity\AirDropEntity;
use Digueloulou12\UltraHardCore\Events\UltraHardCoreEvents;
use Digueloulou12\UltraHardCore\Tasks\WeekStartTask;
use Digueloulou12\UltraHardCore\Utils\Utils;
use pocketmine\entity\EntityDataHelper;
use pocketmine\entity\EntityFactory;
use pocketmine\entity\Human;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\plugin\PluginBase;
use pocketmine\world\World;

class UltraHardCore extends PluginBase
{
    private static UltraHardCoreAPI $API;
    private static self $main;

    public function onEnable(): void
    {
        self::$main = $this;
        $this->saveDefaultConfig();
        self::$API = new UltraHardCoreAPI();
        $this->getScheduler()->scheduleRepeatingTask(new WeekStartTask(), 20 * 60);
        $command = new UltraHardCoreCommand(Utils::getConfigValue("command")[0], Utils::getConfigValue("command")[1], Utils::getConfigValue("command_aliases"));
        $this->getServer()->getCommandMap()->register("UltraHardCoreCommand", $command);
        $this->getServer()->getPluginManager()->registerEvents(new UltraHardCoreEvents(), $this);

        $this->saveResource(Utils::getConfigValue("geometry_file"));
        $this->saveResource(Utils::getConfigValue("airdrop_skin_file"));

        EntityFactory::getInstance()->register(AirDropEntity::class, function (World $world, CompoundTag $nbt): AirDropEntity {
            return new AirDropEntity(EntityDataHelper::parseLocation($nbt, $world), Human::parseSkinNBT($nbt));
        }, ['AirDrop']);
    }

    public function getAPI(): UltraHardCoreAPI
    {
        return self::$API;
    }

    public static function getInstance(): self
    {
        return self::$main;
    }

    public function onDisable(): void
    {
        foreach ($this->getServer()->getWorldManager()->getWorlds() as $world) {
            foreach ($world->getEntities() as $entity) {
                if ($entity instanceof AirDropEntity) {
                    $entity->flagForDespawn();
                }
            }
        }
    }
}