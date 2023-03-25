<?php

namespace Digueloulou12;

use pocketmine\entity\Location;
use pocketmine\entity\projectile\Throwable;
use pocketmine\item\ItemUseResult;
use pocketmine\item\ProjectileItem;
use pocketmine\math\Vector3;
use pocketmine\player\Player;

class EnderPearlItem extends ProjectileItem
{
    public static array $time = [];

    public function getMaxStackSize(): int
    {
        return 16;
    }

    protected function createEntity(Location $location, Player $thrower): Throwable
    {
        return new EnderPearlEntity($location, $thrower);
    }

    public function getThrowForce(): float
    {
        return 1.5;
    }

    public function getCooldownTicks(): int
    {
        return 20;
    }

    public function onClickAir(Player $player, Vector3 $directionVector): ItemUseResult
    {
        if (isset(self::$time[$player->getName()]) and self::$time[$player->getName()] > time()) {
            $time = self::$time[$player->getName()] - time();
            $player->sendPopup(str_replace("{time}", $time, EnderPearlMain::getInstance()->getConfig()->get("popup")));
            return ItemUseResult::FAIL();
        }

        if (parent::onClickAir($player, $directionVector) === ItemUseResult::SUCCESS()) {
            self::$time[$player->getName()] = time() + EnderPearlMain::getInstance()->getConfig()->get("cooldown");
            return ItemUseResult::SUCCESS();
        }
        return ItemUseResult::NONE();
    }
}