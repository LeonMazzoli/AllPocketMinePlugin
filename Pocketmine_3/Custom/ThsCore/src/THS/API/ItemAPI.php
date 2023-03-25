<?php

namespace THS\API;

use pocketmine\item\enchantment\Enchantment;
use pocketmine\item\enchantment\EnchantmentInstance;
use pocketmine\item\Item;
use pocketmine\Player;
use pocketmine\utils\Config;
use THS\Main;

class ItemAPI{
    public static function item(Player $player){
        $slots = new Config(Main::getInstance()->getDataFolder()."slots.json",Config::JSON);
        $language = new Config(Main::getInstance()->getDataFolder()."language.json",Config::JSON);

        foreach ($player->getInventory()->getContents() as $itemclear) {
            $notClear = ["438:16", "438:29", "438:33", "466:0", "378:0"];
            if (!in_array($itemclear->getId() . ":" . $itemclear->getDamage(), $notClear)){
                $player->getInventory()->removeItem($itemclear);
            }
        }

        $player->removeAllEffects();

        $player->setGamemode(0);

        if ($language->get(strtolower($player->getName())) === "en"){
            $player->sendMessage(Main::$prefix."§fYou have been teleported to the hub!");
            $player->getInventory()->setItem($slots->getNested($player->getName().'.settings'), Item::get(Item::DRAGON_BREATH, 0, 1)->setCustomName("§r§a- §fSettings §a-"));
            $player->getInventory()->setItem($slots->getNested("{$player->getName()}.compass"), Item::get(Item::COMPASS, 0, 1)->setCustomName("§r§a- §fGames §a-"));
        }else{
            $player->sendMessage(Main::$prefix . "§fVous avez été téléporté au hub !");
            $player->getInventory()->setItem($slots->getNested("{$player->getName()}.compass"), Item::get(Item::COMPASS, 0, 1)->setCustomName("§r§a- §fJeux §a-"));
            $player->getInventory()->setItem($slots->getNested($player->getName().'.settings'), Item::get(Item::DRAGON_BREATH, 0, 1)->setCustomName("§r§a- §fParamètres §a-"));
        }

        $player->getInventory()->setItem($slots->getNested($player->getName().'.sword'), Item::get(Item::DIAMOND_SWORD, 0, 1)->setCustomName("§r§a- §fPvP §a-"));
        $player->getInventory()->setItem($slots->getNested($player->getName().'.ec'), Item::get(Item::ENDER_CHEST, 0, 1)->setCustomName("§r§a- §fEnderChest §a-"));
    }

    public static function itemHikabrain(Player $player){
        $sword = Item::get(Item::IRON_SWORD, 0, 1);
        $sword->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(Enchantment::SHARPNESS), 3));

        $pickaxe = Item::get(Item::IRON_PICKAXE, 0, 1);
        $pickaxe->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(Enchantment::EFFICIENCY), 3));

        $gres = Item::get(Item::SANDSTONE, 2, 64);

        $apple = Item::get(Item::GOLDEN_APPLE, 0, 64);

        $player->getInventory()->setItem(0, $sword);
        $player->getInventory()->setItem(1, $pickaxe);
        $player->getInventory()->setItem(2, $gres);
        $player->getInventory()->setItem(3, $gres);
        $player->getInventory()->setItem(4, $gres);
        $player->getInventory()->setItem(5, $gres);
        $player->getInventory()->setItem(6, $gres);
        $player->getInventory()->setItem(7, $gres);
        $player->getInventory()->setItem(8, $apple);
    }
}