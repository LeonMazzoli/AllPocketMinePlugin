<?php

namespace Digueloulou12\CustomBox;

use Digueloulou12\CustomBox\BoxEntity\BoxEntity;
use Digueloulou12\CustomBox\Commands\BoxCommand;
use Digueloulou12\CustomBox\Commands\KeyCommand;
use Digueloulou12\CustomBox\Utils\Utils;
use pocketmine\entity\EntityDataHelper;
use pocketmine\entity\EntityFactory;
use pocketmine\entity\Human;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\plugin\PluginBase;
use pocketmine\world\World;

class CustomBox extends PluginBase
{
    private static self $this;

    public function onEnable(): void
    {
        self::$this = $this;
        $this->saveDefaultConfig();

        EntityFactory::getInstance()->register(BoxEntity::class, function (World $world, CompoundTag $nbt): BoxEntity {
            return new BoxEntity(EntityDataHelper::parseLocation($nbt, $world), Human::parseSkinNBT($nbt), $nbt);
        }, ["Box"]);

        $name1 = Utils::getConfigValue("command")[0] ?? "box";
        $desc1 = Utils::getConfigValue("command")[1] ?? "BoxCommand";
        $aliases1 = Utils::getConfigValue("command_aliases") ?? [];

        $name2 = Utils::getConfigValue("commandKey")[0] ?? "box";
        $desc2 = Utils::getConfigValue("commandKey")[1] ?? "BoxCommand";
        $aliases2 = Utils::getConfigValue("commandKey_aliases") ?? [];

        $this->getServer()->getCommandMap()->registerAll("BoxCommand", [
                new BoxCommand($name1, $desc1, $aliases1),
                new KeyCommand($name2, $desc2, $aliases2)
            ]
        );
    }

    public static function getInstance(): self
    {
        return self::$this;
    }
}