<?php

namespace Assassin\Events;

use Assassin\Commands\Sumo;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\item\enchantment\Enchantment;
use pocketmine\item\enchantment\EnchantmentInstance;
use pocketmine\item\Item;
use pocketmine\Player;
use pocketmine\Server;

class Kits implements Listener{
    public static $kit = [];
    public function onInter(PlayerInteractEvent $event){
        $player = $event->getPlayer();
        $item = $event->getItem();

        if ($player->getLevel()->getName() === "ArenePvP"){
            if ($item->getId() === 283){
                $this->gapple($player);
            }
        }elseif ($player->getLevel()->getName() === "AreneJap"){
            if ($item->getId() === 283){
                $this->popo($player);
            }
        }elseif ($player->getLevel()->getName() === "SUMO"){
            if ($item->getId() === 283){
                Sumo::sumo($player);
            }
        }
    }

    public function gapple($player){
        $api = Server::getInstance()->getPluginManager()->getPlugin("FormAPI");
        $form = $api->createSimpleForm(function (Player $player, int $data = null){
            $result = $data;
            if ($result === null){
                return false;
            }
            switch ($result){
                case 0:
                    self::GappleJoueur($player);
                    break;
                case 1:
                    if ($player->hasPermission("vip")){
                        self::GappleVip($player);
                    }
                    break;
                case 2:
                    if ($player->hasPermission("vip+")){
                        self::GappleVipPlus($player);
                    }
                    break;
                case 3:
                    if ($player->hasPermission("tatar")){
                        self::GappleTatar($player);
                    }
                    break;
                case 4:
                    if ($player->hasPermission("legende")){
                        self::GappleLegende($player);
                    }
                    break;
                case 5:
                    if ($player->hasPermission("champion")){
                        self::GappleChampion($player);
                    }
                    break;
                case 6:
                    if ($player->hasPermission("patrones")){
                        self::GappleEl($player);
                    }
                    break;
            }
        });
        $form->setTitle("Kits");
        $form->setContent("Choisi le kit que tu veux:");
        $form->addButton("Joueur");
        $form->addButton("§1VIP");
        $form->addButton("§eVIP+");
        $form->addButton("§3Tatar");
        $form->addButton("§6Légende");
        $form->addButton("§4Champion");
        $form->addButton("§5El patrones");
        $form->sendToPlayer($player);
        return $form;
    }

    public static function GappleJoueur(Player $player){
        $player->getInventory()->removeItem(Item::get(Item::GOLDEN_SWORD, 0, 1));
        $player->getArmorInventory()->clearAll();
        $player->getArmorInventory()->setHelmet(Item::get(Item::DIAMOND_HELMET));
        $player->getArmorInventory()->setChestplate(Item::get(Item::DIAMOND_CHESTPLATE));
        $player->getArmorInventory()->setLeggings(Item::get(Item::DIAMOND_LEGGINGS));
        $player->getArmorInventory()->setBoots(Item::get(Item::DIAMOND_BOOTS));
        $player->getInventory()->removeItem(Item::get(Item::DIAMOND_SWORD, 0, 1));
        $player->getInventory()->removeItem(Item::get(Item::GOLDEN_APPLE, 0, 6));
        $player->getInventory()->removeItem(Item::get(Item::SLIME_BALL, 0, 64));
        $sword = Item::get(Item::DIAMOND_SWORD, 0, 1);
        $sword->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(9), 1));
        $sword->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(17), 5000));
        $player->getInventory()->addItem($sword);
        $player->getInventory()->addItem(Item::get(Item::GOLDEN_APPLE, 0, 6));
        $player->getInventory()->addItem(Item::get(Item::SLIME_BALL, 0, 64));
        self::$kit[$player->getName()] = "joueur";
    }

    public static function GappleVip(Player $player){
        $player->getInventory()->removeItem(Item::get(Item::GOLDEN_SWORD, 0, 1));
        $player->getArmorInventory()->clearAll();
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
        $sword->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(17), 5000));
        $player->getInventory()->addItem($sword);
        $player->getInventory()->addItem(Item::get(Item::GOLDEN_APPLE, 0, 6));
        $player->getInventory()->addItem(Item::get(Item::SLIME_BALL, 0, 70));
        self::$kit[$player->getName()] = "vip";
    }

    public static function GappleVipPlus(Player $player){
        $player->getInventory()->removeItem(Item::get(Item::GOLDEN_SWORD, 0, 1));
        $player->getArmorInventory()->clearAll();
        $helmet = Item::get(Item::DIAMOND_HELMET, 0, 1);
        $helmet->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(0), 1));
        $helmet->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(17), 1));
        $player->getArmorInventory()->setHelmet($helmet);
        $player->getArmorInventory()->setChestplate(Item::get(Item::DIAMOND_CHESTPLATE));
        $player->getArmorInventory()->setLeggings(Item::get(Item::DIAMOND_LEGGINGS));
        $boots = Item::get(Item::DIAMOND_BOOTS);
        $boots->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(0), 1));
        $boots->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(17), 1));
        $player->getArmorInventory()->setBoots($boots);
        $player->getInventory()->removeItem(Item::get(Item::DIAMOND_SWORD, 0, 1));
        $player->getInventory()->removeItem(Item::get(Item::GOLDEN_APPLE, 0, 7));
        $player->getInventory()->removeItem(Item::get(Item::SLIME_BALL, 0, 75));
        $sword = Item::get(Item::DIAMOND_SWORD, 0, 1);
        $sword->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(9), 2));
        $sword->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(17), 5000));
        $player->getInventory()->addItem($sword);
        $player->getInventory()->addItem(Item::get(Item::GOLDEN_APPLE, 0, 7));
        $player->getInventory()->addItem(Item::get(Item::SLIME_BALL, 0, 75));
        self::$kit[$player->getName()] = "vipp";
    }

    public static function GappleTatar(Player $player){
        $player->getInventory()->removeItem(Item::get(Item::GOLDEN_SWORD, 0, 1));
        $player->getArmorInventory()->clearAll();
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
        $sword->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(9), 2));
        $sword->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(17), 5000));
        $player->getInventory()->addItem($sword);
        $player->getInventory()->addItem(Item::get(Item::GOLDEN_APPLE, 0, 8));
        $player->getInventory()->addItem(Item::get(Item::SLIME_BALL, 0, 80));
        self::$kit[$player->getName()] = "tatar";
    }

    public static function GappleLegende(Player $player){
        $player->getInventory()->removeItem(Item::get(Item::GOLDEN_SWORD, 0, 1));
        $player->getArmorInventory()->clearAll();
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
        $sword->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(17), 5000));
        $player->getInventory()->addItem($sword);
        $player->getInventory()->addItem(Item::get(Item::GOLDEN_APPLE, 0, 8));
        $player->getInventory()->addItem(Item::get(Item::SLIME_BALL, 0, 85));
        self::$kit[$player->getName()] = "legende";
    }

    public static function GappleChampion(Player $player){
        $player->getInventory()->removeItem(Item::get(Item::GOLDEN_SWORD, 0, 1));
        $player->getArmorInventory()->clearAll();
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
        $player->getInventory()->removeItem(Item::get(Item::GOLDEN_APPLE, 0, 9));
        $player->getInventory()->removeItem(Item::get(Item::SLIME_BALL, 0, 90));
        $sword = Item::get(Item::DIAMOND_SWORD, 0, 1);
        $sword->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(9), 2));
        $sword->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(17), 5000));
        $player->getInventory()->addItem($sword);
        $player->getInventory()->addItem(Item::get(Item::GOLDEN_APPLE, 0, 9));
        $player->getInventory()->addItem(Item::get(Item::SLIME_BALL, 0, 90));
        self::$kit[$player->getName()] = "champion";
    }

    public static function GappleEl(Player $player){
        $player->getInventory()->removeItem(Item::get(Item::GOLDEN_SWORD, 0, 1));
        $player->getArmorInventory()->clearAll();
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
        $player->getInventory()->removeItem(Item::get(Item::GOLDEN_APPLE, 0, 10));
        $player->getInventory()->removeItem(Item::get(Item::SLIME_BALL, 0, 95));
        $sword = Item::get(Item::DIAMOND_SWORD, 0, 1);
        $sword->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(9), 2));
        $sword->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(17), 5000));
        $player->getInventory()->addItem($sword);
        $player->getInventory()->addItem(Item::get(Item::GOLDEN_APPLE, 0, 10));
        $player->getInventory()->addItem(Item::get(Item::SLIME_BALL, 0, 95));
        self::$kit[$player->getName()] = "el";
    }

    public function popo($player){
        $api = Server::getInstance()->getPluginManager()->getPlugin("FormAPI");
        $form = $api->createSimpleForm(function (Player $player, int $data = null){
            $result = $data;
            if ($result === null){
                return false;
            }
            switch ($result){
                case 0:
                    self::popoJoueur($player);
                    break;
                case 1:
                    if ($player->hasPermission("vip")){
                        self::popoVip($player);
                    }
                    break;
                case 2:
                    if ($player->hasPermission("vip+")){
                        self::popoVipPlus($player);
                    }
                    break;
                case 3:
                    if ($player->hasPermission("tatar")){
                        self::popoTatar($player);
                    }
                    break;
                case 4:
                    if ($player->hasPermission("legende")){
                        self::popoLegende($player);
                    }
                    break;
                case 5:
                    if ($player->hasPermission("champion")){
                        self::popoChampion($player);
                    }
                    break;
                case 6:
                    if ($player->hasPermission("patrones")){
                        self::popoEl($player);
                    }
                    break;
            }
        });
        $form->setTitle("Kits");
        $form->setContent("Choisi le kit que tu veux:");
        $form->addButton("Joueur");
        $form->addButton("§1VIP");
        $form->addButton("§eVIP+");
        $form->addButton("§3Tatar");
        $form->addButton("§6Légende");
        $form->addButton("§4Champion");
        $form->addButton("§5El patrones");
        $form->sendToPlayer($player);
        return $form;
    }

    public static function popoJoueur(Player $player){
        $player->getInventory()->removeItem(Item::get(Item::GOLDEN_SWORD, 0, 1));
        $player->getArmorInventory()->clearAll();
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
        $sword->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(17), 5000));
        $player->getInventory()->addItem($sword);
        $player->getInventory()->addItem(Item::get(Item::ENDER_PEARL, 0, 16));
        $player->getInventory()->addItem(Item::get(Item::POTION, 15, 2));
        $player->getInventory()->addItem(Item::get(Item::SPLASH_POTION, 22, 64));
        self::$kit[$player->getName()] = "joueur";
    }

    public static function popoVip(Player $player){
        $player->getInventory()->removeItem(Item::get(Item::GOLDEN_SWORD, 0, 1));
        $player->getArmorInventory()->clearAll();
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
        $sword->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(17), 5000));
        $player->getInventory()->addItem($sword);
        $player->getInventory()->addItem(Item::get(Item::ENDER_PEARL, 0, 18));
        $player->getInventory()->addItem(Item::get(Item::POTION, 15, 2));
        $player->getInventory()->addItem(Item::get(Item::SPLASH_POTION, 22, 64));
        self::$kit[$player->getName()] = "vip";
    }

    public static function popoVipPlus(Player $player){
        $player->getInventory()->removeItem(Item::get(Item::GOLDEN_SWORD, 0, 1));
        $player->getArmorInventory()->clearAll();
        $helmet = Item::get(Item::DIAMOND_HELMET, 0, 1);
        $helmet->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(0), 1));
        $helmet->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(17), 1));
        $player->getArmorInventory()->setHelmet($helmet);
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
        $sword->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(17), 5000));
        $player->getInventory()->addItem($sword);
        $player->getInventory()->addItem(Item::get(Item::ENDER_PEARL, 0, 20));
        $player->getInventory()->addItem(Item::get(Item::POTION, 15, 2));
        $player->getInventory()->addItem(Item::get(Item::SPLASH_POTION, 22, 64));
        self::$kit[$player->getName()] = "vipp";
    }

    public static function popoTatar(Player $player){
        $player->getInventory()->removeItem(Item::get(Item::GOLDEN_SWORD, 0, 1));
        $player->getArmorInventory()->clearAll();
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
        $sword->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(17), 5000));
        $player->getInventory()->addItem($sword);
        $player->getInventory()->addItem(Item::get(Item::ENDER_PEARL, 0, 22));
        $player->getInventory()->addItem(Item::get(Item::POTION, 15, 2));
        $player->getInventory()->addItem(Item::get(Item::SPLASH_POTION, 22, 64));
        self::$kit[$player->getName()] = "tatar";
    }

    public static function popoLegende(Player $player){
        $player->getInventory()->removeItem(Item::get(Item::GOLDEN_SWORD, 0, 1));
        $player->getArmorInventory()->clearAll();
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
        $sword->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(17), 5000));
        $player->getInventory()->addItem($sword);
        $player->getInventory()->addItem(Item::get(Item::ENDER_PEARL, 0, 24));
        $player->getInventory()->addItem(Item::get(Item::POTION, 15, 2));
        $player->getInventory()->addItem(Item::get(Item::POTION, 16, 1));
        $player->getInventory()->addItem(Item::get(Item::SPLASH_POTION, 22, 64));
        self::$kit[$player->getName()] = "legende";
    }

    public static function popoChampion(Player $player){
        $player->getInventory()->removeItem(Item::get(Item::GOLDEN_SWORD, 0, 1));
        $player->getArmorInventory()->clearAll();
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
        $sword->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(17), 5000));
        $player->getInventory()->addItem($sword);
        $player->getInventory()->addItem(Item::get(Item::ENDER_PEARL, 0, 26));
        $player->getInventory()->addItem(Item::get(Item::POTION, 15, 2));
        $player->getInventory()->addItem(Item::get(Item::POTION, 16, 1));
        $player->getInventory()->addItem(Item::get(Item::SPLASH_POTION, 22, 64));
        self::$kit[$player->getName()] = "champion";
    }

    public static function popoEl(Player $player){
        $player->getInventory()->removeItem(Item::get(Item::GOLDEN_SWORD, 0, 1));
        $player->getArmorInventory()->clearAll();
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
        $sword->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(17), 5000));
        $player->getInventory()->addItem($sword);
        $player->getInventory()->addItem(Item::get(Item::ENDER_PEARL, 0, 28));
        $player->getInventory()->addItem(Item::get(Item::POTION, 15, 2));
        $player->getInventory()->addItem(Item::get(Item::POTION, 16, 1));
        $player->getInventory()->addItem(Item::get(Item::SPLASH_POTION, 22, 64));
        self::$kit[$player->getName()] = "el";
    }
}