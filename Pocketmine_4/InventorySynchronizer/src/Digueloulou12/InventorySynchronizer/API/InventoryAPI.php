<?php

namespace Digueloulou12\InventorySynchronizer\API;

use pocketmine\player\Player;

class InventoryAPI
{
    public static function saveInventory(Player $player, bool $register = false): void
    {
        $contents = [];
        foreach ($player->getInventory()->getContents() as $slot => $item) {
            $contents[$slot] = $item->jsonSerialize();
        }

        $armorContents = [];
        foreach ($player->getArmorInventory()->getContents() as $slot => $item) {
            $armorContents[$slot] = $item->jsonSerialize();
        }

        DatabaseAPI::update($player->getName(), base64_encode(serialize($contents)), base64_encode(serialize($armorContents)), $register);
    }

    public static function setInventoryContents(Player $player): void
    {
        $player->getInventory()->setContents(DatabaseAPI::getInventoryContents($player->getName()));
        $player->getArmorInventory()->setContents(DatabaseAPI::getInventoryContents($player->getName(), true));
    }
}