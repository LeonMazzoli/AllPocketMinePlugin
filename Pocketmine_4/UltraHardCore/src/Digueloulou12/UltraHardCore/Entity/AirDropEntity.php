<?php

namespace Digueloulou12\UltraHardCore\Entity;

use Digueloulou12\UltraHardCore\Utils\Utils;
use pocketmine\entity\Human;
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\item\ItemFactory;

class AirDropEntity extends Human
{
    public $gravity = 0.005;

    public function attack(EntityDamageEvent $source): void
    {
        if ($source->getCause() === EntityDamageEvent::CAUSE_FALL) return;
        foreach (Utils::getConfigValue("airdrop_items") as $item) {
            $source->getEntity()->getWorld()->dropItem($source->getEntity()->getPosition(), ItemFactory::getInstance()->get($item[0], $item[1], $item[2]));
        }
        $this->flagForDespawn();
    }
}