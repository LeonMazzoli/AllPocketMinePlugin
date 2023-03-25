<?php

namespace Digueloulou12\Commands;

use Digueloulou12\Main;
use pocketmine\command\CommandSender;
use pocketmine\command\PluginCommand;
use pocketmine\item\Item;
use pocketmine\Player;

class Furnace extends PluginCommand{
    public function __construct()
    {
        $command = explode(":", Main::getConfigAPI()->getConfigValue("furnace"));
        parent::__construct($command[0], Main::getInstance());
        if (isset($command[1])) $this->setDescription($command[1]);
        if (isset($command[2])) $this->setPermission($command[2]);
        $this->setAliases(Main::getConfigAPI()->getConfigValue("furnace_aliases"));
    }

    public function execute(CommandSender $player, string $commandLabel, array $args)
    {
        if (!$player instanceof Player){
            $player->sendMessage(Main::getConfigAPI()->getConfigValue("noplayer"));
            return;
        }

        $command = explode(":", Main::getConfigAPI()->getConfigValue("furnace"));
        if (isset($command[2])){
            if (!$player->hasPermission($command[2])){
                $player->sendMessage(Main::getConfigAPI()->getConfigValue("noperm"));
                return;
            }
        }

        $item = $player->getInventory()->getItemInHand();
        if ($item->getId() == Item::RAW_BEEF) {
            $player->getInventory()->setItemInHand(Item::get(Item::COOKED_BEEF, 0, $item->getCount()));
            $player->sendMessage(Main::getConfigAPI()->getConfigValue("furnace_msg"));
        } elseif ($item->getId() == Item::RAW_PORKCHOP) {
            $player->getInventory()->setItemInHand(Item::get(Item::COOKED_PORKCHOP, 0, $item->getCount()));
            $player->sendMessage(Main::getConfigAPI()->getConfigValue("furnace_msg"));
        } elseif ($item->getId() == Item::RAW_FISH) {
            $player->getInventory()->setItemInHand(Item::get(Item::COOKED_FISH, 0, $item->getCount()));
            $player->sendMessage(Main::getConfigAPI()->getConfigValue("furnace_msg"));
        } elseif ($item->getId() == Item::RAW_CHICKEN) {
            $player->getInventory()->setItemInHand(Item::get(Item::COOKED_CHICKEN, 0, $item->getCount()));
            $player->sendMessage(Main::getConfigAPI()->getConfigValue("furnace_msg"));
        } elseif ($item->getId() == Item::RAW_RABBIT) {
            $player->getInventory()->setItemInHand(Item::get(Item::COOKED_RABBIT, 0, $item->getCount()));
            $player->sendMessage(Main::getConfigAPI()->getConfigValue("furnace_msg"));
        } elseif ($item->getId() == Item::RAW_MUTTON) {
            $player->getInventory()->setItemInHand(Item::get(Item::COOKED_MUTTON, 0, $item->getCount()));
            $player->sendMessage(Main::getConfigAPI()->getConfigValue("furnace_msg"));
        } elseif ($item->getId() == Item::RAW_SALMON) {
            $player->getInventory()->setItemInHand(Item::get(Item::COOKED_SALMON, 0, $item->getCount()));
            $player->sendMessage(Main::getConfigAPI()->getConfigValue("furnace_msg"));
        } elseif ($item->getId() == Item::DIAMOND_ORE) {
            $player->getInventory()->setItemInHand(Item::get(Item::DIAMOND, 0, $item->getCount()));
            $player->sendMessage(Main::getConfigAPI()->getConfigValue("furnace_msg"));
        } elseif ($item->getId() == Item::IRON_ORE) {
            $player->getInventory()->setItemInHand(Item::get(Item::IRON_INGOT, 0, $item->getCount()));
            $player->sendMessage(Main::getConfigAPI()->getConfigValue("furnace_msg"));
        } elseif ($item->getId() == Item::GOLD_ORE) {
            $player->getInventory()->setItemInHand(Item::get(Item::GOLD_INGOT, 0, $item->getCount()));
            $player->sendMessage(Main::getConfigAPI()->getConfigValue("furnace_msg"));
        } elseif ($item->getId() == Item::QUARTZ_ORE) {
            $player->getInventory()->setItemInHand(Item::get(Item::QUARTZ, 0, $item->getCount()));
            $player->sendMessage(Main::getConfigAPI()->getConfigValue("furnace_msg"));
        } elseif ($item->getId() == Item::COBBLESTONE) {
            $player->getInventory()->setItemInHand(Item::get(Item::STONE, 0, $item->getCount()));
            $player->sendMessage(Main::getConfigAPI()->getConfigValue("furnace_msg"));
        } elseif ($item->getId() == Item::CLAY_BALL) {
            $player->getInventory()->setItemInHand(Item::get(Item::BRICK, 0, $item->getCount()));
            $player->sendMessage(Main::getConfigAPI()->getConfigValue("furnace_msg"));
        } elseif ($item->getId() == Item::NETHERRACK) {
            $player->getInventory()->setItemInHand(Item::get(Item::NETHERBRICK, 0, $item->getCount()));
            $player->sendMessage(Main::getConfigAPI()->getConfigValue("furnace_msg"));
        } elseif ($item->getId() == Item::SAND) {
            $player->getInventory()->setItemInHand(Item::get(Item::GLASS, 0, $item->getCount()));
            $player->sendMessage(Main::getConfigAPI()->getConfigValue("furnace_msg"));
        } elseif ($item->getId() == Item::REDSTONE_ORE) {
            $player->getInventory()->setItemInHand(Item::get(Item::REDSTONE, 0, $item->getCount()));
            $player->sendMessage(Main::getConfigAPI()->getConfigValue("furnace_msg"));
        } elseif ($item->getId() == Item::EMERALD_ORE) {
            $player->getInventory()->setItemInHand(Item::get(Item::EMERALD, 0, $item->getCount()));
            $player->sendMessage(Main::getConfigAPI()->getConfigValue("furnace_msg"));
        } elseif ($item->getId() == Item::COAL_ORE) {
            $player->getInventory()->setItemInHand(Item::get(Item::COAL, 0, $item->getCount()));
            $player->sendMessage(Main::getConfigAPI()->getConfigValue("furnace_msg"));
        } elseif ($item->getId() == Item::LOG) {
            $player->getInventory()->setItemInHand(Item::get(Item::COAL, 0, $item->getCount()));
            $player->sendMessage(Main::getConfigAPI()->getConfigValue("furnace_msg"));
        } else $player->sendMessage(Main::getConfigAPI()->getConfigValue("furnace_nofurnace"));
    }
}