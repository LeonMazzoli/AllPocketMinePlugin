<?php

namespace THS\Commands\Boutique;

use pocketmine\command\CommandSender;
use pocketmine\command\PluginCommand;
use THS\API\PlayersAPI;
use THS\Main;

class MyPb extends PluginCommand{
    public function __construct(Main $main)
    {
        parent::__construct("mypb", $main);
    }

    public function execute(CommandSender $player, string $commandLabel, array $args)
    {
        $money = PlayersAPI::getInfo($player, "boutique");
        $player->sendMessage(Main::$prefix."Vous avez§a $money §fpoint(s) boutique !");
    }
}