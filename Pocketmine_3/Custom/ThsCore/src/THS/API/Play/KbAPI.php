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

class KbAPI{
    public static function startKB(Player $player){
        $config = new Config(Main::getInstance()->getDataFolder()."config.yml",Config::YAML);
        $x = $config->get("KB")[0];
        $y = $config->get("KB")[1];
        $z = $config->get("KB")[2];
        $monde = $config->get("KB")[3];
        $pos = new Position($x, $y, $z, Server::getInstance()->getLevelByName($monde));
        $player->teleport($pos);
        Game::removeItem($player);
        $player->getInventory()->addItem(Item::get(Item::BLAZE_ROD, 0, 1));
        $bow = Item::get(Item::BOW, 0, 1);
        $bow->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(Enchantment::INFINITY), 1));
        $bow->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(Enchantment::PUNCH), 7));
        $player->getInventory()->addItem($bow);
        $player->getInventory()->addItem(Item::get(Item::ARROW, 0, 1));
        $player->getInventory()->setItem(8, Item::get(Item::FEATHER, 0, 1)->setCustomName("Fly"));
        $player->setHealth($player->getMaxHealth());
    }
}