<?php

namespace Assassin\ModePvP;

use pocketmine\entity\Effect;
use pocketmine\entity\EffectInstance;
use pocketmine\item\enchantment\Enchantment;
use pocketmine\item\enchantment\EnchantmentInstance;
use pocketmine\item\Item;
use pocketmine\level\Position;
use pocketmine\Player;
use pocketmine\Server;

class Mode{
    public static function snow(Player $player){
        $player->getInventory()->clearAll();
        $player->getArmorInventory()->clearAll();
        $player->getInventory()->addItem(Item::get(Item::GOLDEN_APPLE, 0, 3));
        $player->getInventory()->addItem(Item::get(Item::SNOWBALL, 0, 1000));
        $player->addEffect(new EffectInstance(Effect::getEffect(1), 999999, 2, false));
    }

    public static function arc(Player $player){
        $player->getInventory()->clearAll();
        $player->getArmorInventory()->clearAll();
        $player->getInventory()->removeItem(Item::get(Item::COMPASS, 0, 64));
        $bow = Item::get(Item::BOW, 0, 1);
        $bow->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(Enchantment::UNBREAKING), 5000));
        $bow->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(Enchantment::POWER), 1));
        $bow->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(Enchantment::INFINITY), 1));
        $player->getInventory()->addItem($bow);
        $player->getInventory()->addItem(Item::get(Item::ARROW, 0, 1));
        $player->getInventory()->addItem(Item::get(Item::GOLDEN_APPLE, 0, 3));
        $player->addEffect(new EffectInstance(Effect::getEffect(1), 999999, 2, false));
    }

    public static function tpsnow(Player $player){
        $kitpvp = Server::getInstance()->getLevelByName("snowpvp");
        switch (mt_rand(1, 11)){
            case 1:
                $player->teleport(new Position(5, 67, -83, $kitpvp));
                break;
            case 2:
                $player->teleport(new Position(-52, 54, -63, $kitpvp));
                break;
            case 3:
                $player->teleport(new Position(0, 51, 0, $kitpvp));
                break;
            case 4:
                $player->teleport(new Position(-38, 67, 89, $kitpvp));
                break;
            case 5:
                $player->teleport(new Position(-15, 59, 103, $kitpvp));
                break;
            case 6:
                $player->teleport(new Position(29, 66, 90, $kitpvp));
                break;
            case 7:
                $player->teleport(new Position(83, 64, 65, $kitpvp));
                break;
            case 8:
                $player->teleport(new Position(-70, 67, -10, $kitpvp));
                break;
            case 9:
                $player->teleport(new Position(-89, 58, 57, $kitpvp));
                break;
            case 10:
                $player->teleport(new Position(71, 75, 5, $kitpvp));
                break;
            case 11:
                $player->teleport(new Position(68, 84, -60, $kitpvp));
                break;
        }
    }

    public static function tparc(Player $player){
        $kitpvp = Server::getInstance()->getLevelByName("arcpvp");
        switch (mt_rand(1, 11)) {
            case 1:
                $player->teleport(new Position(5, 67, -83, $kitpvp));
                break;
            case 2:
                $player->teleport(new Position(-52, 54, -63, $kitpvp));
                break;
            case 3:
                $player->teleport(new Position(0, 51, 0, $kitpvp));
                break;
            case 4:
                $player->teleport(new Position(-38, 67, 89, $kitpvp));
                break;
            case 5:
                $player->teleport(new Position(-15, 59, 103, $kitpvp));
                break;
            case 6:
                $player->teleport(new Position(29, 66, 90, $kitpvp));
                break;
            case 7:
                $player->teleport(new Position(83, 64, 65, $kitpvp));
                break;
            case 8:
                $player->teleport(new Position(-70, 67, -10, $kitpvp));
                break;
            case 9:
                $player->teleport(new Position(-89, 58, 57, $kitpvp));
                break;
            case 10:
                $player->teleport(new Position(71, 75, 5, $kitpvp));
                break;
            case 11:
                $player->teleport(new Position(68, 84, -60, $kitpvp));
                break;
        }
    }
}