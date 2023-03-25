<?php

namespace Assassin\Command;

use Assassin\Forms\ShopForm;
use Assassin\ShopMain;
use pocketmine\command\CommandSender;
use pocketmine\command\PluginCommand;
use pocketmine\Player;

class Shop extends PluginCommand{
    private $main;
    public function __construct(ShopMain $main)
    {
        parent::__construct("shop", $main);
        $this->setDescription("Ouvre la boutique d'assassin");
        $this->setAliases(["boutique"]);
        $this->main = $main;
    }

    public function execute(CommandSender $player, string $commandLabel, array $args)
    {
        if ($player instanceof Player){
            ShopForm::ShopForm($player);
        }
    }
}