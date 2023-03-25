<?php

namespace THS\Items;

use pocketmine\event\Listener;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\item\ItemIds;
use THS\Forms\CompassForms;

class Compass implements Listener{
    public function onInter(PlayerInteractEvent $event)
    {
        $player = $event->getPlayer();
        $world = $player->getLevel()->getName();

        if ($event->getItem()->getId() === ItemIds::DIAMOND_SWORD){
            if ($event->getPlayer()->getLevel()->getName() !== "Hub") return;
            CompassForms::diamondForm($player);
            return;
        }

        if ($event->getItem()->getId() !== 345) return;

        switch ($world){
            case "Hub":
                CompassForms::formMenu($player);
                break;
            case "Gapple":
                CompassForms::gappleKit($player);
                break;
            case "Popo":
                CompassForms::popoKit($player);
                break;
        }
    }
}