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

class GappleAPI{
    public static $kit = [];
    public static function startGapple(Player $player){
        $config = new Config(Main::getInstance()->getDataFolder()."config.yml",Config::YAML);
        $x = $config->get("Gapple")[0];
        $y = $config->get("Gapple")[1];
        $z = $config->get("Gapple")[2];
        $monde = $config->get("Gapple")[3];
        $pos = new Position($x, $y, $z, Server::getInstance()->getLevelByName($monde));
        $player->teleport($pos);
        Game::removeItem($player);
        $player->getInventory()->setItem(4, Item::get(Item::COMPASS, 0, 1)->setCustomName("§r§a- §fKits §a-"));
    }

    // Kit
    public static function player(Player $player){
        $config = new Config(Main::getInstance()->getDataFolder()."slotsgapple.json",Config::JSON);
        Game::removeItem($player);
        $player->getArmorInventory()->setHelmet(Item::get(Item::DIAMOND_HELMET));
        $player->getArmorInventory()->setChestplate(Item::get(Item::DIAMOND_CHESTPLATE));
        $player->getArmorInventory()->setLeggings(Item::get(Item::DIAMOND_LEGGINGS));
        $player->getArmorInventory()->setBoots(Item::get(Item::DIAMOND_BOOTS));
        $player->getInventory()->removeItem(Item::get(Item::DIAMOND_SWORD, 0, 1));
        $player->getInventory()->removeItem(Item::get(Item::GOLDEN_APPLE, 0, 6));
        $player->getInventory()->removeItem(Item::get(Item::SLIME_BALL, 0, 64));
        $sword = Item::get(Item::DIAMOND_SWORD, 0, 1);
        $sword->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(9), 1));
        $player->getInventory()->setItem($config->get($player->getName())["sword"], $sword);
        $player->getInventory()->setItem($config->get($player->getName())["apple"], Item::get(Item::GOLDEN_APPLE, 0, 6));
        $player->getInventory()->setItem($config->get($player->getName())["heal"], Item::get(Item::SLIME_BALL, 0, 64));
        self::$kit[$player->getName()] = "player";
    }

    public static function vip(Player $player){
        $config = new Config(Main::getInstance()->getDataFolder()."slotsgapple.json",Config::JSON);
        Game::removeItem($player);
        $player->getArmorInventory()->setHelmet(Item::get(Item::DIAMOND_HELMET));
        $player->getArmorInventory()->setChestplate(Item::get(Item::DIAMOND_CHESTPLATE));
        $player->getArmorInventory()->setLeggings(Item::get(Item::DIAMOND_LEGGINGS));
        $boots = Item::get(Item::DIAMOND_BOOTS);
        $boots->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(0), 1));
        $boots->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(17), 1));
        $player->getArmorInventory()->setBoots($boots);
        $player->getInventory()->removeItem(Item::get(Item::DIAMOND_SWORD, 0, 1));
        $player->getInventory()->removeItem(Item::get(Item::GOLDEN_APPLE, 0, 6));
        $player->getInventory()->removeItem(Item::get(Item::SLIME_BALL, 0, 70));
        $sword = Item::get(Item::DIAMOND_SWORD, 0, 1);
        $sword->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(9), 1));
        $player->getInventory()->setItem($config->get($player->getName())["sword"], $sword);
        $player->getInventory()->setItem($config->get($player->getName())["apple"], Item::get(Item::GOLDEN_APPLE, 0, 6));
        $player->getInventory()->setItem($config->get($player->getName())["heal"], Item::get(Item::SLIME_BALL, 0, 70));
        self::$kit[$player->getName()] = "vip";
    }

    public static function tatar(Player $player){
        $config = new Config(Main::getInstance()->getDataFolder()."slotsgapple.json",Config::JSON);
        Game::removeItem($player);
        $helmet = Item::get(Item::DIAMOND_HELMET, 0, 1);
        $helmet->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(0), 1));
        $helmet->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(17), 1));
        $player->getArmorInventory()->setHelmet($helmet);
        $player->getArmorInventory()->setChestplate(Item::get(Item::DIAMOND_CHESTPLATE));
        $leggin = Item::get(Item::DIAMOND_LEGGINGS);
        $leggin->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(0), 1));
        $leggin->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(17), 1));
        $player->getArmorInventory()->setLeggings($leggin);
        $boots = Item::get(Item::DIAMOND_BOOTS);
        $boots->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(0), 1));
        $boots->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(17), 1));
        $player->getArmorInventory()->setBoots($boots);
        $player->getInventory()->removeItem(Item::get(Item::DIAMOND_SWORD, 0, 1));
        $player->getInventory()->removeItem(Item::get(Item::GOLDEN_APPLE, 0, 8));
        $player->getInventory()->removeItem(Item::get(Item::SLIME_BALL, 0, 80));
        $sword = Item::get(Item::DIAMOND_SWORD, 0, 1);
        $sword->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(9), 1));
        $sword->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(17), 1));
        $player->getInventory()->setItem($config->get($player->getName())["sword"], $sword);
        $player->getInventory()->setItem($config->get($player->getName())["apple"], Item::get(Item::GOLDEN_APPLE, 0, 8));
        $player->getInventory()->setItem($config->get($player->getName())["heal"], Item::get(Item::SLIME_BALL, 0, 80));
        self::$kit[$player->getName()] = "tatar";
    }

    public static function legende(Player $player){
        $config = new Config(Main::getInstance()->getDataFolder()."slotsgapple.json",Config::JSON);
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
        $player->getInventory()->removeItem(Item::get(Item::GOLDEN_APPLE, 0, 8));
        $player->getInventory()->removeItem(Item::get(Item::SLIME_BALL, 0, 85));
        $sword = Item::get(Item::DIAMOND_SWORD, 0, 1);
        $sword->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(9), 2));
        $sword->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(17), 1));
        $player->getInventory()->setItem($config->get($player->getName())["sword"], $sword);
        $player->getInventory()->setItem($config->get($player->getName())["apple"], Item::get(Item::GOLDEN_APPLE, 0, 8));
        $player->getInventory()->setItem($config->get($player->getName())["heal"], Item::get(Item::SLIME_BALL, 0, 85));
        self::$kit[$player->getName()] = "legende";
    }

    public static function champion(Player $player){
        $config = new Config(Main::getInstance()->getDataFolder()."slotsgapple.json",Config::JSON);
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
        $player->getInventory()->removeItem(Item::get(Item::GOLDEN_APPLE, 0, 9));
        $player->getInventory()->removeItem(Item::get(Item::SLIME_BALL, 0, 90));
        $sword = Item::get(Item::DIAMOND_SWORD, 0, 1);
        $sword->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(9), 2));
        $sword->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(17), 2));
        $player->getInventory()->setItem($config->get($player->getName())["sword"], $sword);
        $player->getInventory()->setItem($config->get($player->getName())["apple"], Item::get(Item::GOLDEN_APPLE, 0, 9));
        $player->getInventory()->setItem($config->get($player->getName())["heal"], Item::get(Item::SLIME_BALL, 0, 90));
        self::$kit[$player->getName()] = "champion";
    }

    public static function patrones(Player $player){
        $config = new Config(Main::getInstance()->getDataFolder()."slotsgapple.json",Config::JSON);
        Game::removeItem($player);
        $helmet = Item::get(Item::DIAMOND_HELMET, 0, 1);
        $helmet->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(0), 2));
        $helmet->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(17), 2));
        $player->getArmorInventory()->setHelmet($helmet);
        $chest = Item::get(Item::DIAMOND_CHESTPLATE);
        $chest->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(0), 2));
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
        $player->getInventory()->removeItem(Item::get(Item::GOLDEN_APPLE, 0, 10));
        $player->getInventory()->removeItem(Item::get(Item::SLIME_BALL, 0, 95));
        $sword = Item::get(Item::DIAMOND_SWORD, 0, 1);
        $sword->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(9), 2));
        $sword->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(17), 2));
        $player->getInventory()->setItem($config->get($player->getName())["sword"], $sword);
        $player->getInventory()->setItem($config->get($player->getName())["apple"], Item::get(Item::GOLDEN_APPLE, 0, 10));
        $player->getInventory()->setItem($config->get($player->getName())["heal"], Item::get(Item::SLIME_BALL, 0, 95));
        self::$kit[$player->getName()] = "patrones";
    }

    public static function supreme(Player $player){
        $config = new Config(Main::getInstance()->getDataFolder()."slotsgapple.json",Config::JSON);
        Game::removeItem($player);
        $helmet = Item::get(Item::DIAMOND_HELMET, 0, 1);
        $helmet->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(0), 2));
        $helmet->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(17), 2));
        $player->getArmorInventory()->setHelmet($helmet);
        $chest = Item::get(Item::DIAMOND_CHESTPLATE);
        $chest->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(0), 2));
        $chest->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(17), 2));
        $player->getArmorInventory()->setChestplate($chest);
        $leggin = Item::get(Item::DIAMOND_LEGGINGS);
        $leggin->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(0), 2));
        $leggin->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(17), 2));
        $player->getArmorInventory()->setLeggings($leggin);
        $boots = Item::get(Item::DIAMOND_BOOTS);
        $boots->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(0), 2));
        $boots->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(17), 2));
        $player->getArmorInventory()->setBoots($boots);
        $player->getInventory()->removeItem(Item::get(Item::DIAMOND_SWORD, 0, 1));
        $player->getInventory()->removeItem(Item::get(Item::GOLDEN_APPLE, 0, 10));
        $player->getInventory()->removeItem(Item::get(Item::SLIME_BALL, 0, 95));
        $sword = Item::get(Item::DIAMOND_SWORD, 0, 1);
        $sword->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(9), 3));
        $sword->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(17), 2));
        $player->getInventory()->setItem($config->get($player->getName())["sword"], $sword);
        $player->getInventory()->setItem($config->get($player->getName())["apple"], Item::get(Item::GOLDEN_APPLE, 0, 12));
        $player->getInventory()->setItem($config->get($player->getName())["heal"], Item::get(Item::SLIME_BALL, 0, 100));
        self::$kit[$player->getName()] = "supreme";
    }
}