<?php

namespace Digueloulou12\LobbyCore\Events;

use Digueloulou12\LobbyCore\Tasks\UpdateTask;
use pocketmine\entity\projectile\EnderPearl;
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\event\entity\ProjectileHitEvent;
use pocketmine\event\Listener;
use pocketmine\event\server\QueryRegenerateEvent;
use pocketmine\item\VanillaItems;
use pocketmine\player\Player;

class OtherEvents implements Listener
{
    public function onQuery(QueryRegenerateEvent $event)
    {
        $event->getQueryInfo()->setPlayerCount(UpdateTask::$connect);
        $event->getQueryInfo()->setMaxPlayerCount(UpdateTask::$maxConnect);
    }

    public function onDamage(EntityDamageEvent $event): void
    {
        $event->cancel();
    }

    public function onLaunch(ProjectileHitEvent $event): void
    {
        $item = $event->getEntity();
        if ($item instanceof EnderPearl) {
            $player = $event->getEntity()->getOwningEntity();
            if ($player instanceof Player) {
                $player->getInventory()->setItemInHand(VanillaItems::ENDER_PEARL());
            }
        }
    }
}