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

class HealStickAPI
{
    public static function startHeal(Player $player)
    {
        $config = new Config(Main::getInstance()->getDataFolder()."config.yml",Config::YAML);
        $x = $config->get("Heal")[0];
        $y = $config->get("Heal")[1];
        $z = $config->get("Heal")[2];
        $monde = $config->get("Heal")[3];
        $pos = new Position($x, $y, $z, Server::getInstance()->getLevelByName($monde));
        $player->teleport($pos);
        Game::removeItem($player);
        self::kit($player);
    }

    public static function kit(Player $player){
        $helmet = Item::get(Item::DIAMOND_HELMET, 0, 1);
        $chestplate = Item::get(Item::DIAMOND_CHESTPLATE, 0, 1);
        $leggings = Item::get(Item::DIAMOND_LEGGINGS, 0, 1);
        $boots = Item::get(Item::DIAMOND_BOOTS, 0, 1);

        $helmet->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(Enchantment::PROTECTION), 1));
        $helmet->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(Enchantment::UNBREAKING), 1));

        $chestplate->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(Enchantment::PROTECTION), 1));
        $chestplate->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(Enchantment::UNBREAKING), 1));

        $leggings->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(Enchantment::PROTECTION), 1));
        $leggings->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(Enchantment::UNBREAKING), 1));

        $boots->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(Enchantment::PROTECTION), 1));
        $boots->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(Enchantment::UNBREAKING), 1));

        $player->getArmorInventory()->setHelmet($helmet);
        $player->getArmorInventory()->setChestplate($chestplate);
        $player->getArmorInventory()->setLeggings($leggings);
        $player->getArmorInventory()->setBoots($boots);


        $sword = Item::get(Item::DIAMOND_SWORD, 0, 1);
        $sword->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(Enchantment::UNBREAKING), 2));
        $sword->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(Enchantment::SHARPNESS), 2));
        $player->getInventory()->addItem($sword);

        $player->getInventory()->addItem(Item::get(Item::GOLDEN_APPLE, 0, 4));

        $player->getInventory()->setItem(2, Item::get(Item::MINECART, 0, 1));
        $player->getInventory()->setItem(3, Item::get(Item::MINECART, 0, 1));
        $player->getInventory()->setItem(4, Item::get(Item::MINECART, 0, 1));
        $player->getInventory()->setItem(5, Item::get(Item::MINECART, 0, 1));
        $player->getInventory()->addItem(Item::get(Item::SHEARS, 0, 12));
    }
}