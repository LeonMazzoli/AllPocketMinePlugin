<?php

namespace THS\API;

use pocketmine\item\Item;
use pocketmine\Player;
use pocketmine\utils\Config;
use THS\Main;

class Game{
    public static function removeItem(Player $player){
        $player->getInventory()->removeItem(Item::get(Item::DIAMOND_SWORD, 0, 1));
        $player->getInventory()->removeItem(Item::get(Item::COMPASS, 0, 1));
        $player->getInventory()->removeItem(Item::get(Item::DRAGON_BREATH, 0, 1));
        $player->getInventory()->removeItem(Item::get(Item::ENDER_CHEST, 0, 1));
    }

    public static function addItem(Player $player){
        $slots = new Config(Main::getInstance()->getDataFolder()."slots.json",Config::JSON);
        if (LanguageAPI::getLanguage($player) === "en"){
            $player->getInventory()->setItem($slots->getNested($player->getName().'.settings'), Item::get(Item::DRAGON_BREATH, 0, 1)->setCustomName("§r§a- §fSettings §a-"));
            $player->getInventory()->setItem($slots->getNested("{$player->getName()}.compass"), Item::get(Item::COMPASS, 0, 1)->setCustomName("§r§a- §fGames §a-"));
        }else{
            $player->getInventory()->setItem($slots->getNested("{$player->getName()}.compass"), Item::get(Item::COMPASS, 0, 1)->setCustomName("§r§a- §fJeux §a-"));
            $player->getInventory()->setItem($slots->getNested($player->getName().'.settings'), Item::get(Item::DRAGON_BREATH, 0, 1)->setCustomName("§r§a- §fParamètres §a-"));
        }

        $player->getInventory()->setItem($slots->getNested("{$player->getName()}.sword"), Item::get(Item::DIAMOND_SWORD, 0, 1)->setCustomName("§r§a- §fPvP §a-"));
        $player->getInventory()->setItem($slots->getNested("{$player->getName()}.ec"), Item::get(Item::ENDER_CHEST, 0, 1)->setCustomName("§r§a- §fEnderChest §a-"));
    }
}