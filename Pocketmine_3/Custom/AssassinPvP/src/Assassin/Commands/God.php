<?php

namespace Assassin\Commands;

use Assassin\Main;
use pocketmine\command\CommandSender;
use pocketmine\command\PluginCommand;
use pocketmine\Player;

class God extends PluginCommand{
    public static $god = [];
    private $main;
    public function __construct(Main $main)
    {
        parent::__construct("god", $main);
        $this->setDescription("Passe en godmod");
        $this->setPermission("god.use");
        $this->main = $main;
    }

    public function execute(CommandSender $player, string $commandLabel, array $args)
    {
        if ($player instanceof Player){
            if ($player->hasPermission("god.use")){
                if (empty(self::$god[$player->getName()])){
                    self::$god[$player->getName()] = $player->getName();
                    $player->sendMessage(Main::$prefix . "§fVous venez de passé en godmod !");
                }else{
                    unset(self::$god[$player->getName()]);
                    $player->sendMessage(Main::$prefix . "§fVous venez de quitter le godmod !");
                }
            }else{
                $player->sendMessage(Main::$prefix . "§fVous n'avez pas la permission !");
            }
        }else{
            $player->sendMessage(Main::$prefix . "§fLa console doit être executer en jeu !");
        }
    }
}