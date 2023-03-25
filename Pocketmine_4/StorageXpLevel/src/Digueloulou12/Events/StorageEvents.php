<?php

namespace Digueloulou12\Events;

use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\event\block\BlockBreakEvent;
use pocketmine\event\block\BlockPlaceEvent;
use Digueloulou12\Forms\StorageForms;
use pocketmine\event\Listener;
use pocketmine\utils\Config;
use Digueloulou12\Storage;

class StorageEvents implements Listener
{
    public function onPlace(BlockPlaceEvent $event)
    {
        if ($event->isCancelled()) return;
        if ($event->getBlock()->getId() === (int)Storage::getConfigValue("id")) {
            $storage = new Config(Storage::getInstance()->getDataFolder() . "Storage.json", Config::JSON);
            $pos = "{$event->getBlock()->getPosition()->x}:{$event->getBlock()->getPosition()->y}:{$event->getBlock()->getPosition()->z}:{$event->getBlock()->getPosition()->getWorld()->getDisplayName()}";
            $storage->set($pos, 0);
            $storage->save();
        }
    }

    public function onBreak(BlockBreakEvent $event)
    {
        if ($event->isCancelled()) return;
        if ($event->getBlock()->getId() === (int)Storage::getConfigValue("id")) {
            $storage = new Config(Storage::getInstance()->getDataFolder() . "Storage.json", Config::JSON);
            $pos = "{$event->getBlock()->getPosition()->x}:{$event->getBlock()->getPosition()->y}:{$event->getBlock()->getPosition()->z}:{$event->getBlock()->getPosition()->getWorld()->getDisplayName()}";
            if ($storage->exists($pos)) {
                $event->getPlayer()->getXpManager()->addXpLevels($storage->get($pos));
                $storage->remove($pos);
                $storage->save();
            }
        }
    }

    public function onUse(PlayerInteractEvent $event)
    {
        if ($event->isCancelled()) return;
        if ($event->getBlock()->getId() === (int)Storage::getConfigValue("id")) {
            $storage = new Config(Storage::getInstance()->getDataFolder() . "Storage.json", Config::JSON);
            $pos = "{$event->getBlock()->getPosition()->x}:{$event->getBlock()->getPosition()->y}:{$event->getBlock()->getPosition()->z}:{$event->getBlock()->getPosition()->getWorld()->getDisplayName()}";
            if ($storage->exists($pos)) {
                if ($event->getAction() === PlayerInteractEvent::RIGHT_CLICK_BLOCK) {
                    $event->cancel();
                    StorageForms::mainForm($event->getPlayer(), $pos);
                }
            }
        }
    }
}