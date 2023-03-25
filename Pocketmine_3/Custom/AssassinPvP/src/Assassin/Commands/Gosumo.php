<?php

namespace Assassin\Commands;

use Assassin\Main;
use pocketmine\command\CommandSender;
use pocketmine\command\PluginCommand;
use pocketmine\item\Item;
use pocketmine\level\Position;
use pocketmine\Player;
use pocketmine\Server;

class Gosumo extends PluginCommand{
    public function __construct(Main $main)
    {
        parent::__construct("gosumo",$main);
        $this->setPermission("gosumo.use");
        $this->setDescription("Téléporte en sumo");
    }

    public function execute(CommandSender $player, string $commandLabel, array $args)
    {
        if (!($player instanceof Player)) return;
        $world = Server::getInstance()->getLevelByName("SUMO");
        $player->teleport(new Position(362, 2, 61, $world));
        $player->sendMessage(Main::$prefix."Vous avez été téléporté en sumo !");
        $player->getInventory()->setItem(4, Item::get(Item::GOLDEN_SWORD, 0, 1)->setCustomName("§r§fKits Sumo"));
    }
}