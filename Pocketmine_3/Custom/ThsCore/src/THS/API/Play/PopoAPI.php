<?php

namespace THS\API\Play;

use pocketmine\item\enchantment\Enchantment;
use pocketmine\item\enchantment\EnchantmentInstance;
use pocketmine\item\Item;
use pocketmine\level\Position;
use pocketmine\Player;
use pocketmine\Server;
use pocketmine\utils\Config;
use THS\API\Game;
use THS\Main;

class PopoAPI{
    public static $kit = [];
    public static function start(Player $player){
        $config = new Config(Main::getInstance()->getDataFolder()."config.yml",Config::YAML);
        $x = $config->get("Popo")[0];
        $y = $config->get("Popo")[1];
        $z = $config->get("Popo")[2];
        $monde = $config->get("Popo")[3];
        $pos = new Position($x, $y, $z, Server::getInstance()->getLevelByName($monde));
        $player->teleport($pos);
        Game::removeItem($player);
        $player->getInventory()->setItem(4, Item::get(Item::COMPASS, 0, 1)->setCustomName("§a- §fKits §a-"));
    }

    public static function player(Player $player){
        Game::removeItem($player);
        $player->getArmorInventory()->setHelmet(Item::get(Item::DIAMOND_HELMET));
        $player->getArmorInventory()->setChestplate(Item::get(Item::DIAMOND_CHESTPLATE));
        $player->getArmorInventory()->setLeggings(Item::get(Item::DIAMOND_LEGGINGS));
        $player->getArmorInventory()->setBoots(Item::get(Item::DIAMOND_BOOTS));
        $player->getInventory()->removeItem(Item::get(Item::DIAMOND_SWORD, 0, 1));
        $player->getInventory()->removeItem(Item::get(Item::SPLASH_POTION, 22, 64));
        $player->getInventory()->removeItem(Item::get(Item::POTION, 15, 64));
        $player->getInventory()->removeItem(Item::get(Item::ENDER_PEARL, 0, 64));
        $sword = Item::get(Item::DIAMOND_SWORD, 0, 1);
        $sword->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(9), 1));
        $player->getInventory()->addItem($sword);
        $player->getInventory()->addItem(Item::get(Item::ENDER_PEARL, 0, 16));
        $player->getInventory()->addItem(Item::get(Item::POTION, 15, 2));
        $player->getInventory()->addItem(Item::get(Item::SPLASH_POTION, 22, 64));
        self::$kit[$player->getName()] = "player";
    }

    public static function vip(Player $player){
        Game::removeItem($player);
        $player->getArmorInventory()->setHelmet(Item::get(Item::DIAMOND_HELMET));
        $player->getArmorInventory()->setChestplate(Item::get(Item::DIAMOND_CHESTPLATE));
        $player->getArmorInventory()->setLeggings(Item::get(Item::DIAMOND_LEGGINGS));
        $boots = Item::get(Item::DIAMOND_BOOTS);
        $boots->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(0), 1));
        $boots->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(17), 1));
        $player->getArmorInventory()->setBoots($boots);
        $player->getInventory()->removeItem(Item::get(Item::DIAMOND_SWORD, 0, 1));
        $player->getInventory()->removeItem(Item::get(Item::SPLASH_POTION, 22, 64));
        $player->getInventory()->removeItem(Item::get(Item::POTION, 15, 64));
        $player->getInventory()->removeItem(Item::get(Item::ENDER_PEARL, 0, 64));
        $sword = Item::get(Item::DIAMOND_SWORD, 0, 1);
        $sword->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(9), 1));
        $player->getInventory()->addItem($sword);
        $player->getInventory()->addItem(Item::get(Item::ENDER_PEARL, 0, 18));
        $player->getInventory()->addItem(Item::get(Item::POTION, 15, 2));
        $player->getInventory()->addItem(Item::get(Item::SPLASH_POTION, 22, 64));
        self::$kit[$player->getName()] = "vip";
    }

    public static function tatar(Player $player){
        Game::removeItem($player);
        $helmet = Item::get(Item::DIAMOND_HELMET, 0, 1);
        $helmet->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(0), 1));
        $helmet->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(17), 1));
        $player->getArmorInventory()->setHelmet($helmet);
        $player->getArmorInventory()->setChestplate(Item::getItem(Item::DIAMOND_CHESTPLATE, 0, 1));
        $leggin = Item::get(Item::DIAMOND_LEGGINGS);
        $leggin->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(0), 1));
        $leggin->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(17), 1));
        $player->getArmorInventory()->setLeggings($leggin);
        $boots = Item::get(Item::DIAMOND_BOOTS);
        $boots->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(0), 1));
        $boots->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(17), 1));
        $player->getArmorInventory()->setBoots($boots);
        $player->getInventory()->removeItem(Item::get(Item::DIAMOND_SWORD, 0, 1));
        $player->getInventory()->removeItem(Item::get(Item::SPLASH_POTION, 22, 64));
        $player->getInventory()->removeItem(Item::get(Item::POTION, 15, 64));
        $player->getInventory()->removeItem(Item::get(Item::ENDER_PEARL, 0, 64));
        $sword = Item::get(Item::DIAMOND_SWORD, 0, 1);
        $sword->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(9), 1));
        $player->getInventory()->addItem($sword);
        $player->getInventory()->addItem(Item::get(Item::ENDER_PEARL, 0, 22));
        $player->getInventory()->addItem(Item::get(Item::POTION, 15, 2));
        $player->getInventory()->addItem(Item::get(Item::SPLASH_POTION, 22, 64));
        self::$kit[$player->getName()] = "tatar";
    }

    public static function legende(Player $player){
        Game::removeItem($player);
        $helmet = Item::get(Item::DIAMOND_HELMET, 0, 1);
        $helmet->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(0), 1));
        $helmet->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(17), 2));
        $player->getArmorInventory()->setHelmet($helmet);
        $chest = Item::get(Item::DIAMOND_CHESTPLATE);
        $chest->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(0), 1));
        $chest->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(17), 2));
        $player->getArmorInventory()->setChestplate($chest);
        $leggin = Item::get(Item::DIAMOND_LEGGINGS);
        $leggin->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(0), 1));
        $leggin->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(17), 2));
        $player->getArmorInventory()->setLeggings($leggin);
        $boots = Item::get(Item::DIAMOND_BOOTS);
        $boots->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(0), 1));
        $boots->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(17), 2));
        $player->getArmorInventory()->setBoots($boots);
        $player->getInventory()->removeItem(Item::get(Item::DIAMOND_SWORD, 0, 1));
        $player->getInventory()->removeItem(Item::get(Item::SPLASH_POTION, 22, 64));
        $player->getInventory()->removeItem(Item::get(Item::POTION, 15, 64));
        $player->getInventory()->removeItem(Item::get(Item::ENDER_PEARL, 0, 64));
        $sword = Item::get(Item::DIAMOND_SWORD, 0, 1);
        $sword->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(9), 2));
        $sword->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(17), 1));
        $player->getInventory()->addItem($sword);
        $player->getInventory()->addItem(Item::get(Item::ENDER_PEARL, 0, 24));
        $player->getInventory()->addItem(Item::get(Item::POTION, 15, 2));
        $player->getInventory()->addItem(Item::get(Item::POTION, 16, 1));
        $player->getInventory()->addItem(Item::get(Item::SPLASH_POTION, 22, 64));
        self::$kit[$player->getName()] = "legende";
    }

    public static function champion(Player $player){
        Game::removeItem($player);
        $helmet = Item::get(Item::DIAMOND_HELMET, 0, 1);
        $helmet->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(0), 1));
        $helmet->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(17), 2));
        $player->getArmorInventory()->setHelmet($helmet);
        $chest = Item::get(Item::DIAMOND_CHESTPLATE);
        $chest->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(0), 1));
        $chest->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(17), 2));
        $player->getArmorInventory()->setChestplate($chest);
        $leggin = Item::get(Item::DIAMOND_LEGGINGS);
        $leggin->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(0), 1));
        $leggin->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(17), 2));
        $player->getArmorInventory()->setLeggings($leggin);
        $boots = Item::get(Item::DIAMOND_BOOTS);
        $boots->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(0), 1));
        $boots->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(17), 2));
        $player->getArmorInventory()->setBoots($boots);
        $player->getInventory()->removeItem(Item::get(Item::DIAMOND_SWORD, 0, 1));
        $player->getInventory()->removeItem(Item::get(Item::SPLASH_POTION, 22, 64));
        $player->getInventory()->removeItem(Item::get(Item::POTION, 15, 64));
        $player->getInventory()->removeItem(Item::get(Item::ENDER_PEARL, 0, 64));
        $sword = Item::get(Item::DIAMOND_SWORD, 0, 1);
        $sword->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(9), 2));
        $sword->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(17), 1));
        $player->getInventory()->addItem($sword);
        $player->getInventory()->addItem(Item::get(Item::ENDER_PEARL, 0, 26));
        $player->getInventory()->addItem(Item::get(Item::POTION, 15, 2));
        $player->getInventory()->addItem(Item::get(Item::POTION, 16, 1));
        $player->getInventory()->addItem(Item::get(Item::SPLASH_POTION, 22, 64));
        self::$kit[$player->getName()] = "champion";
    }

    public static function patrones(Player $player){
        Game::removeItem($player);
        $helmet = Item::get(Item::DIAMOND_HELMET, 0, 1);
        $helmet->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(0), 1));
        $helmet->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(17), 2));
        $player->getArmorInventory()->setHelmet($helmet);
        $chest = Item::get(Item::DIAMOND_CHESTPLATE);
        $chest->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(0), 1));
        $chest->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(17), 2));
        $player->getArmorInventory()->setChestplate($chest);
        $leggin = Item::get(Item::DIAMOND_LEGGINGS);
        $leggin->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(0), 1));
        $leggin->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(17), 2));
        $player->getArmorInventory()->setLeggings($leggin);
        $boots = Item::get(Item::DIAMOND_BOOTS);
        $boots->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(0), 1));
        $boots->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(17), 2));
        $player->getArmorInventory()->setBoots($boots);
        $player->getInventory()->removeItem(Item::get(Item::DIAMOND_SWORD, 0, 1));
        $player->getInventory()->removeItem(Item::get(Item::SPLASH_POTION, 22, 64));
        $player->getInventory()->removeItem(Item::get(Item::POTION, 15, 64));
        $player->getInventory()->removeItem(Item::get(Item::ENDER_PEARL, 0, 64));
        $sword = Item::get(Item::DIAMOND_SWORD, 0, 1);
        $sword->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(9), 2));
        $sword->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(17), 2));
        $player->getInventory()->addItem($sword);
        $player->getInventory()->addItem(Item::get(Item::ENDER_PEARL, 0, 28));
        $player->getInventory()->addItem(Item::get(Item::POTION, 15, 2));
        $player->getInventory()->addItem(Item::get(Item::POTION, 16, 1));
        $player->getInventory()->addItem(Item::get(Item::SPLASH_POTION, 22, 64));
        self::$kit[$player->getName()] = "patrones";
    }

    public static function supreme(Player $player){
        Game::removeItem($player);
        $helmet = Item::get(Item::DIAMOND_HELMET, 0, 1);
        $helmet->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(0), 2));
        $helmet->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(17), 2));
        $player->getArmorInventory()->setHelmet($helmet);
        $chest = Item::get(Item::DIAMOND_CHESTPLATE);
        $chest->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(0), 1));
        $chest->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(17), 2));
        $player->getArmorInventory()->setChestplate($chest);
        $leggin = Item::get(Item::DIAMOND_LEGGINGS);
        $leggin->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(0), 1));
        $leggin->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(17), 2));
        $player->getArmorInventory()->setLeggings($leggin);
        $boots = Item::get(Item::DIAMOND_BOOTS);
        $boots->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(0), 2));
        $boots->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(17), 2));
        $player->getArmorInventory()->setBoots($boots);
        $player->getInventory()->removeItem(Item::get(Item::DIAMOND_SWORD, 0, 1));
        $player->getInventory()->removeItem(Item::get(Item::SPLASH_POTION, 22, 64));
        $player->getInventory()->removeItem(Item::get(Item::POTION, 15, 64));
        $player->getInventory()->removeItem(Item::get(Item::ENDER_PEARL, 0, 64));
        $sword = Item::get(Item::DIAMOND_SWORD, 0, 1);
        $sword->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(9), 2));
        $sword->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(17), 2));
        $player->getInventory()->addItem($sword);
        $player->getInventory()->addItem(Item::get(Item::ENDER_PEARL, 0, 30));
        $player->getInventory()->addItem(Item::get(Item::POTION, 15, 2));
        $player->getInventory()->addItem(Item::get(Item::POTION, 16, 2));
        $player->getInventory()->addItem(Item::get(Item::SPLASH_POTION, 22, 64));
        self::$kit[$player->getName()] = "supreme";
    }
}