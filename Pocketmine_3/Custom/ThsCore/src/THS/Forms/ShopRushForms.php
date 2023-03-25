<?php

namespace THS\Forms;

use jojoe77777\FormAPI\SimpleForm;
use pocketmine\item\Item;
use pocketmine\item\LeatherBoots;
use pocketmine\item\LeatherCap;
use pocketmine\item\LeatherPants;
use pocketmine\item\LeatherTunic;
use HiroTeam\Hikabrain\rush\RushParty;
use pocketmine\Player;
use pocketmine\utils\Color;
use THS\Main;

class ShopRushForms{
    public static function form(Player $player){
        $form = new SimpleForm(function (Player $player, int $data = null){
            if ($data === null) return true;
            switch ($data){
                case 0:
                    self::block($player);
                    break;
                case 1:
                    self::tools($player);
                    break;
                case 2:
                    self::armor($player);
                    break;
                case 3:
                    self::special($player);
                    break;
            }
        });
        $form->setTitle("§a- §fShop §a-");
        $form->setContent("Choisissez ce que vous voulez acheter:");
        $form->addButton("Blocs");
        $form->addButton("Outils");
        $form->addButton("Armure");
        $form->addButton("Spécial");
        $form->addButton("§l§cRetour");
        $form->sendToPlayer($player);
        return $form;
    }

    public static function block(Player $player){
        $form = new SimpleForm(function (Player $player, int $data = null){
            if ($data === null) return true;
            switch ($data) {
                case 0:
                    $playerTeam = RushParty::$players[$player->getName()]["team"];
                    if ($playerTeam === "blue") {
                        self::shop(Item::get(Item::WOOL, 11, 32), Item::get(Item::BRICK, 0, 5), $player);
                    } else self::shop(Item::get(Item::WOOL, 14, 32), Item::get(Item::BRICK, 0, 5), $player);
                    break;
                case 1:
                    self::shop(Item::get(Item::SANDSTONE, 2, 32), Item::get(Item::IRON_INGOT, 0, 10), $player);
                    break;
                case 2:
                    self::shop(Item::get(Item::PLANKS, 0, 32), Item::get(Item::DIAMOND, 0, 2), $player);
                    break;
                case 3:
                    self::shop(Item::get(Item::OBSIDIAN, 0, 8), Item::get(Item::EMERALD, 0, 2), $player);
                    break;
                case 4:
                    self::form($player);
                    break;
            }
        });
        $form->setTitle("§a- §fShop §a-");
        $form->setContent("Choisissez ce que vous voulez acheter:");
        $form->addButton("§a32§f Laine vs §a5§f briques");
        $form->addButton("§a32§f Grès vs §a10§f fer");
        $form->addButton("§a32§f Planche vs §a2§f diamant");
        $form->addButton("§a8§f Obsidienne vs §a2§f Emeraude");
        $form->addButton("§c§lRetour");
        $form->sendToPlayer($player);
        return $form;
    }

    public static function tools(Player $player){
        $form = new SimpleForm(function (Player $player, int $data = null){
            if ($data === null) return true;
            switch ($data){
                case 0:
                    self::shop(Item::get(Item::IRON_SWORD, 0, 1), Item::get(Item::BRICK, 0, 20), $player);
                    break;
                case 1:
                    self::shop(Item::get(Item::DIAMOND_SWORD, 0, 1), Item::get(Item::EMERALD, 0, 1), $player);
                    break;
                case 2:
                    self::shop(Item::get(Item::WOODEN_PICKAXE, 0, 1), Item::get(Item::BRICK, 0, 5), $player);
                    break;
                case 3:
                    self::shop(Item::get(Item::DIAMOND_PICKAXE, 0, 1), Item::get(Item::GOLD_INGOT, 0, 5), $player);
                    break;
                case 4:
                    self::shop(Item::get(Item::WOODEN_AXE, 0, 1), Item::get(Item::GOLD_INGOT, 0, 3), $player);
                    break;
                case 5:
                    self::shop(Item::get(Item::BOW, 0, 1), Item::get(Item::DIAMOND, 0, 2), $player);
                    break;
                case 6:
                    self::shop(Item::get(Item::ARROW, 0, 8), Item::get(Item::BRICK, 0, 16), $player);
                    break;
                case 7:
                    self::form($player);
                    break;
            }
        });
        $form->setTitle("§a- §fShop §a-");
        $form->setContent("Choisissez ce que vous voulez acheter:");
        $form->addButton("Epee fer vs §a20§f briques");
        $form->addButton("Epee diams vs §a1§f emeraude");
        $form->addButton("Pioche bois vs §a5§f briques");
        $form->addButton("Pioche diams vs §a5§f or");
        $form->addButton("Hache bois vs §a3§f or");
        $form->addButton("Arc vs §a2§f diamants");
        $form->addButton("§a8§f Fleches vs §a16§f briques");
        $form->addButton("§l§cRetour");
        $form->sendToPlayer($player);
        return $form;
    }

    public static function armor(Player $player){
        $form = new SimpleForm(function (Player $player, int $data = null){
            if ($data === null) return true;
            switch ($data){
                case 0:
                    if ($player->getInventory()->contains(Item::get(Item::BRICK, 0, 20))) {
                        $playerTeam = RushParty::$players[$player->getName()]["team"];
                        switch ($playerTeam) {
                            case 'blue':
                                $color = new Color(22, 156, 157);
                                break;
                            case 'red':
                                $color = new Color(176, 46, 38);
                                break;
                            default:
                                break;
                        }
                        $helmet = Item::get(Item::LEATHER_HELMET, 0, 1);
                        if ($helmet instanceof LeatherCap) $helmet->setCustomColor($color);
                        $chestplate = Item::get(Item::LEATHER_CHESTPLATE, 0, 1);
                        if ($chestplate instanceof LeatherTunic) $chestplate->setCustomColor($color);
                        $leggings = Item::get(Item::LEATHER_LEGGINGS, 0, 1);
                        if ($leggings instanceof LeatherPants) $leggings->setCustomColor($color);
                        $boots = Item::get(Item::LEATHER_BOOTS, 0, 1);
                        if ($boots instanceof LeatherBoots) $boots->setCustomColor($color);
                        $armorInv = $player->getArmorInventory();
                        $armorInv->setHelmet($helmet);
                        $armorInv->setChestplate($chestplate);
                        $armorInv->setLeggings($leggings);
                        $armorInv->setBoots($boots);
                        $player->getInventory()->removeItem(Item::get(Item::BRICK, 0, 20));
                    }else $player->sendMessage(Main::$prefix."Vous n'avez pas assez de brique !");
                    break;
                case 1:
                    self::shop(Item::get(Item::IRON_CHESTPLATE, 0, 1), Item::get(Item::DIAMOND, 0, 2), $player);
                    break;
                case 2:
                    self::shop(Item::get(Item::DIAMOND_CHESTPLATE, 0, 1), Item::get(Item::EMERALD, 0, 3), $player);
                    break;
                case 3:
                    self::form($player);
                    break;
            }
        });
        $form->setTitle("§a- §fShop §a-");
        $form->setContent("Choisissez ce que vous voulez acheter:");
        $form->addButton("Full cuir vs§a 20§f briques");
        $form->addButton("Plastron fer vs §a2§f diamants");
        $form->addButton("Plastron diams vs §a3§f emeraudes");
        $form->addButton("§l§cRetour");
        $form->sendToPlayer($player);
        return $form;
    }

    public static function special(Player $player){
        $form = new SimpleForm(function (Player $player, int $data = null){
            if ($data === null) return true;
            switch ($data){
                case 0:
                    self::shop(Item::get(Item::TNT, 0, 1), Item::get(Item::BRICK, 0, 16), $player);
                    break;
                case 1:
                    self::shop(Item::get(Item::FLINT_AND_STEEL, 0, 1), Item::get(Item::GOLD_INGOT, 0, 5), $player);
                    break;
                case 2:
                    self::shop(Item::get(Item::ENDER_PEARL, 0, 1), Item::get(Item::DIAMOND, 0, 4), $player);
                    break;
                case 3:
                    self::shop(Item::get(Item::GOLDEN_APPLE, 0, 5), Item::get(Item::GOLD_INGOT, 0, 5), $player);
                    break;
                case 4:
                    self::shop(Item::get(Item::END_STONE, 0, 1), Item::get(Item::BRICK, 0, 1), $player);
                    break;
                case 5:
                    self::form($player);
                    break;
            }
        });
        $form->setTitle("§a- §fShop §a-");
        $form->setContent("Choisissez ce que vous voulez acheter:");
        $form->addButton("§a1§f TNT vs §a16§f briques");
        $form->addButton("§a1§f briquet vs§a 5 §for");
        $form->addButton("§a1 §fEnder pearl vs§a 4§f diamants");
        $form->addButton("§a5§f pomme en or vs§a 5 §for");
        $form->addButton("§a1§f FlyBlock vs §aSoon...");
        $form->addButton("§l§cRetour");
        $form->sendToPlayer($player);
        return $form;
    }

    public static function shop(Item $item_shop, Item $item_sell, Player $player){
        if (!$player->getInventory()->canAddItem($item_shop)){
            $player->sendMessage(Main::$prefix."Vous n'avez pas assez de place dans votre inventaire !");
            return;
        }

        if (!$player->getInventory()->contains($item_sell)){
            $player->sendMessage(Main::$prefix."Vous n'avez pas ce qu'il faut pour acheter cet article !");
            return;
        }

        $player->getInventory()->addItem($item_shop);
        $player->getInventory()->removeItem($item_sell);

        $player->sendMessage(Main::$prefix."L'achat a bien été éffétuer ! ");
    }
}