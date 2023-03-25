<?php

namespace Assassin\ModePvP;

use Assassin\Events\KillEvent;
use pocketmine\item\enchantment\Enchantment;
use pocketmine\item\enchantment\EnchantmentInstance;
use pocketmine\item\Item;
use pocketmine\Player;

class KitSumo{
    public static function sumopopo(Player $player){
        $player->setHealth($player->getMaxHealth());
        $player->setFood($player->getMaxFood());
        KillEvent::$sumo[$player->getName()] = "popo";
        $player->getInventory()->clearAll();
        $player->getArmorInventory()->clearAll();
        $helmet = Item::get(Item::IRON_HELMET);
        $helmet->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(Enchantment::UNBREAKING), 5));
        $helmet->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(Enchantment::PROTECTION), 4));
        $player->getArmorInventory()->setHelmet($helmet);
        $chest = Item::get(Item::IRON_CHESTPLATE);
        $chest->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(Enchantment::UNBREAKING), 5));
        $chest->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(Enchantment::PROTECTION), 4));
        $player->getArmorInventory()->setChestplate($chest);
        $leggings = Item::get(Item::IRON_LEGGINGS);
        $leggings->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(Enchantment::UNBREAKING), 5));
        $leggings->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(Enchantment::PROTECTION), 4));
        $player->getArmorInventory()->setLeggings($leggings);
        $boots = Item::get(Item::IRON_BOOTS);
        $boots->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(Enchantment::UNBREAKING), 5));
        $boots->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(Enchantment::PROTECTION), 4));
        $player->getArmorInventory()->setBoots($boots);
        $player->getInventory()->addItem(Item::get(Item::ENDER_PEARL, 0, 16));
    }

    public static function kitsnow(Player $player){
        $player->setHealth($player->getMaxHealth());
        $player->setFood($player->getMaxFood());
        KillEvent::$sumo[$player->getName()] = "snow";
        $player->getInventory()->clearAll();
        $player->getArmorInventory()->clearAll();
        $helmet = Item::get(Item::IRON_HELMET);
        $helmet->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(Enchantment::UNBREAKING), 5));
        $helmet->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(Enchantment::PROTECTION), 4));
        $player->getArmorInventory()->setHelmet($helmet);
        $chest = Item::get(Item::IRON_CHESTPLATE);
        $chest->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(Enchantment::UNBREAKING), 5));
        $chest->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(Enchantment::PROTECTION), 4));
        $player->getArmorInventory()->setChestplate($chest);
        $leggings = Item::get(Item::IRON_LEGGINGS);
        $leggings->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(Enchantment::UNBREAKING), 5));
        $leggings->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(Enchantment::PROTECTION), 4));
        $player->getArmorInventory()->setLeggings($leggings);
        $boots = Item::get(Item::IRON_BOOTS);
        $boots->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(Enchantment::UNBREAKING), 5));
        $boots->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(Enchantment::PROTECTION), 4));
        $player->getArmorInventory()->setBoots($boots);
        $player->getInventory()->addItem(Item::get(Item::SNOWBALL, 0, 32));
    }

    public static function sumobasique(Player $player){
        $player->setHealth($player->getMaxHealth());
        $player->setFood($player->getMaxFood());
        KillEvent::$sumo[$player->getName()] = "basique";
        $player->getInventory()->clearAll();
        $player->getArmorInventory()->clearAll();
        $helmet = Item::get(Item::IRON_HELMET);
        $helmet->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(Enchantment::UNBREAKING), 5));
        $helmet->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(Enchantment::PROTECTION), 4));
        $player->getArmorInventory()->setHelmet($helmet);
        $chest = Item::get(Item::IRON_CHESTPLATE);
        $chest->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(Enchantment::UNBREAKING), 5));
        $chest->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(Enchantment::PROTECTION), 4));
        $player->getArmorInventory()->setChestplate($chest);
        $leggings = Item::get(Item::IRON_LEGGINGS);
        $leggings->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(Enchantment::UNBREAKING), 5));
        $leggings->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(Enchantment::PROTECTION), 4));
        $player->getArmorInventory()->setLeggings($leggings);
        $boots = Item::get(Item::IRON_BOOTS);
        $boots->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(Enchantment::UNBREAKING), 5));
        $boots->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(Enchantment::PROTECTION), 4));
        $player->getArmorInventory()->setBoots($boots);
    }

    public static function sumoarc(Player $player){
        KillEvent::$sumo[$player->getName()] = "arc";
        $player->getInventory()->clearAll();
        $player->getArmorInventory()->clearAll();
        $helmet = Item::get(Item::IRON_HELMET);
        $helmet->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(Enchantment::UNBREAKING), 5));
        $helmet->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(Enchantment::PROTECTION), 4));
        $player->getArmorInventory()->setHelmet($helmet);
        $chest = Item::get(Item::IRON_CHESTPLATE);
        $chest->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(Enchantment::UNBREAKING), 5));
        $chest->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(Enchantment::PROTECTION), 4));
        $player->getArmorInventory()->setChestplate($chest);
        $leggings = Item::get(Item::IRON_LEGGINGS);
        $leggings->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(Enchantment::UNBREAKING), 5));
        $leggings->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(Enchantment::PROTECTION), 4));
        $player->getArmorInventory()->setLeggings($leggings);
        $boots = Item::get(Item::IRON_BOOTS);
        $boots->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(Enchantment::UNBREAKING), 5));
        $boots->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(Enchantment::PROTECTION), 4));
        $player->getArmorInventory()->setBoots($boots);
        $bow = Item::get(Item::BOW);
        $bow->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(Enchantment::UNBREAKING), 5000));
        $player->getInventory()->addItem($bow);
        $player->getInventory()->addItem(Item::get(Item::ARROW, 0, 32));
    }
}