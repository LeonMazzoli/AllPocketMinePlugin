<?php

namespace Command\Commands;

use Command\Evonia;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\item\Item;
use pocketmine\Player;
use pocketmine\utils\Config;

class Furnace extends Command{
    private $main;
    public function __construct(Evonia $main)
    {
        $config = new Config(Evonia::getInstance()->getDataFolder() . "config.yml", Config::YAML);
        parent::__construct("furnace", $config->get("desc.furnace"), "/furnace", ["four"]);
    }

    public function execute(CommandSender $player, string $commandLabel, array $args)
    {
        $config = new Config(Evonia::getInstance()->getDataFolder() . "config.yml", Config::YAML);
        if ($player instanceof Player){
            if ($player->hasPermission("furnace.use")){
                $item = $player->getInventory()->getItemInHand();
                if($item->getId() == Item::RAW_BEEF){
                    $player->getInventory()->setItemInHand(Item::get(ITEM::COOKED_BEEF,0,$item->getCount()));
                }elseif($item->getId() == ITEM::RAW_PORKCHOP){
                    $player->getInventory()->setItemInHand(Item::get(ITEM::COOKED_PORKCHOP,0,$item->getCount()));
                }elseif($item->getId() == ITEM::RAW_FISH){
                    $player->getInventory()->setItemInHand(Item::get(ITEM::COOKED_FISH,0,$item->getCount()));
                }elseif($item->getId() == ITEM::RAW_CHICKEN){
                    $player->getInventory()->setItemInHand(Item::get(ITEM::COOKED_CHICKEN,0,$item->getCount()));
                }elseif($item->getId() == ITEM::RAW_RABBIT){
                    $player->getInventory()->setItemInHand(Item::get(ITEM::COOKED_RABBIT,0,$item->getCount()));
                }elseif($item->getId() == ITEM::RAW_MUTTON){
                    $player->getInventory()->setItemInHand(Item::get(ITEM::COOKED_MUTTON,0,$item->getCount()));
                }elseif($item->getId() == ITEM::RAW_SALMON){
                    $player->getInventory()->setItemInHand(Item::get(ITEM::COOKED_SALMON,0,$item->getCount()));
                }elseif($item->getId() == ITEM::DIAMOND_ORE){
                    $player->getInventory()->setItemInHand(Item::get(ITEM::DIAMOND,0,$item->getCount()));
                }elseif($item->getId() == ITEM::IRON_ORE){
                    $player->getInventory()->setItemInHand(Item::get(ITEM::IRON_INGOT,0,$item->getCount()));
                }elseif($item->getId() == ITEM::GOLD_ORE){
                    $player->getInventory()->setItemInHand(Item::get(ITEM::GOLD_INGOT,0,$item->getCount()));
                }elseif($item->getId() == ITEM::QUARTZ_ORE){
                    $player->getInventory()->setItemInHand(Item::get(ITEM::QUARTZ,0,$item->getCount()));
                }elseif($item->getId() == ITEM::COBBLESTONE){
                    $player->getInventory()->setItemInHand(Item::get(ITEM::STONE,0,$item->getCount()));
                }elseif($item->getId() == ITEM::CLAY_BALL){
                    $player->getInventory()->setItemInHand(Item::get(ITEM::BRICK,0,$item->getCount()));
                }elseif($item->getId() == ITEM::NETHERRACK){
                    $player->getInventory()->setItemInHand(Item::get(ITEM::NETHERBRICK,0,$item->getCount()));
                }elseif($item->getId() == ITEM::SAND){
                    $player->getInventory()->setItemInHand(Item::get(ITEM::GLASS,0,$item->getCount()));
                }elseif($item->getId() == ITEM::REDSTONE_ORE){
                    $player->getInventory()->setItemInHand(Item::get(ITEM::REDSTONE,0,$item->getCount()));
                }elseif($item->getId() == ITEM::EMERALD_ORE){
                    $player->getInventory()->setItemInHand(Item::get(ITEM::EMERALD,0,$item->getCount()));
                }elseif($item->getId() == ITEM::COAL_ORE){
                    $player->getInventory()->setItemInHand(Item::get(ITEM::COAL,0,$item->getCount()));
                }elseif($item->getId() == ITEM::LOG){
                    $player->getInventory()->setItemInHand(Item::get(ITEM::COAL,0,$item->getCount()));
                }elseif($item->getId() == Item::CLAY){
                    $player->getInventory()->setItemInHand(Item::get(ITEM::HARDENED_CLAY,0,$item->getCount()));
                }else{
                    $player->sendMessage($config->get("no.furnace"));
                    return false;
                }
                $player->sendMessage($config->get("good.furnace"));
                return true;
            }else{
                $player->sendMessage($config->get("noperm.furnace"));
            }
        }else{
            $player->sendMessage($config->get("console.furnace"));
        }
    }
}