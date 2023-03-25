<?php

namespace THS\Items;

use pocketmine\event\Listener;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\event\player\PlayerMoveEvent;
use pocketmine\item\Item;
use THS\Forms\SettingsForms;

class Settings implements Listener{
    public function onInter(PlayerInteractEvent $event){
        $player = $event->getPlayer();

        if ($event->getItem()->getId() !== 437) return;
        SettingsForms::settings($player);
    }
}