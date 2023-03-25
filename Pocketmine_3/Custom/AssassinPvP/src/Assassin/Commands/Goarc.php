<?php

namespace Assassin\Commands;

use Assassin\Main;
use Assassin\ModePvP\Mode;
use pocketmine\command\CommandSender;
use pocketmine\command\PluginCommand;
use pocketmine\item\Item;
use pocketmine\Player;

class Goarc extends PluginCommand{
    private $main;
    public function __construct(Main $main)
    {
        parent::__construct("goarc", $main);
        $this->setDescription("Téléporte dans le mode de jeu archer");
        $this->setPermission("goarc.use");
        $this->main = $main;
    }

    public function execute(CommandSender $player, string $commandLabel, array $args)
    {
        if ($player instanceof Player){
            $player->getInventory()->removeItem(Item::get(Item::DRAGON_BREATH, 0, 1999));
            Mode::tparc($player);
            Mode::arc($player);
        }
    }
}