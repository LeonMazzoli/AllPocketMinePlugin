<?php

namespace THS\Commands;

use pocketmine\command\CommandSender;
use pocketmine\command\PluginCommand;
use pocketmine\item\Item;
use pocketmine\level\Position;
use pocketmine\Player;
use pocketmine\Server;
use THS\Main;

class Reunion extends PluginCommand
{
    public function __construct(Main $main)
    {
        parent::__construct("reunion", $main);
        $this->setPermission("reunion");
    }

    public function execute(CommandSender $player, string $commandLabel, array $args)
    {
        if ($player instanceof Player) {
            if ($player->hasPermission('reunion')) {
                $player->getInventory()->clearAll();
                $player->getArmorInventory()->clearAll();
                $player->setGamemode(0);
                $player->getInventory()->addItem(Item::get(Item::CHAINMAIL_HELMET, 0, 1));
                $player->getInventory()->addItem(Item::get(Item::CHAIN_CHESTPLATE, 0, 1));
                $player->getInventory()->addItem(Item::get(Item::CHAIN_LEGGINGS, 0, 1));
                $player->getInventory()->addItem(Item::get(Item::CHAINMAIL_BOOTS, 0, 1));
                $player->getInventory()->addItem(Item::get(Item::WRITABLE_BOOK, 0, 1));
                $player->getInventory()->addItem(Item::get(Item::STONE_SWORD, 0, 1));
                $player->getInventory()->addItem(Item::get(Item::DIAMOND_HOE, 0, 1)->setCustomName("Salle de Réunion"));
                $player->teleport(new Position(947, 8, 984, Server::getInstance()->getLevelByName("Hub")));
            }
        }
    }
}
