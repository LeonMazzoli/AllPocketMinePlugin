<?php

namespace Assassin\Commands;

use Assassin\Main;
use pocketmine\command\CommandSender;
use pocketmine\command\PluginCommand;
use pocketmine\item\Item;
use pocketmine\level\Position;
use pocketmine\Player;
use pocketmine\Server;

class Gogapple extends PluginCommand{
    private $main;
    public function __construct(Main $main)
    {
        parent::__construct("gogapple", $main);
        $this->setDescription("Téléporte dans le mode de jeu gapple");
        $this->setPermission("gogapple.use");
        $this->main = $main;
    }

    public function execute(CommandSender $player, string $commandLabel, array $args)
    {
        if ($player instanceof Player){
            $player->getInventory()->setItem(4, Item::get(Item::GOLDEN_SWORD, 0, 1));
            $monde = Server::getInstance()->getLevelByName("ArenePvP");
            $player->teleport(new Position(-28, 211, 20, $monde));
            $player->getInventory()->removeItem(Item::get(Item::DRAGON_BREATH, 0, 1999));
        }
    }
}