<?php

namespace Assassin\Commands;

use Assassin\Main;
use pocketmine\command\CommandSender;
use pocketmine\command\PluginCommand;
use pocketmine\Player;
use pocketmine\Server;

class Laserui extends PluginCommand{
    private $main;
    public function __construct(Main $main)
    {
        parent::__construct("laserui", $main);
        $this->setDescription("Ouvre l'interface de lasergame");
        $this->setPermission("laser.use");
        $this->main = $main;
    }

    public function execute(CommandSender $player, string $commandLabel, array $args)
    {
        if ($player instanceof Player){
            $this->laserui($player);
        }
    }

    public function laserui($player){
        $api = Server::getInstance()->getPluginManager()->getPlugin("FormAPI");
        $form = $api->createSimpleForm(function (Player $player, int $data = null){
            $result = $data;
            if ($result === null){
                return false;
            }
            switch ($result){
                case 0:
                    Server::getInstance()->dispatchCommand($player, "laserjoin");
                    break;
                case 1:
                    $this->multi($player);
                    break;
            }
        });
        $form->setTitle("LaserGame");
        $form->setContent("Choisie le type de lasergame que tu veux:");
        $form->addButton("Solo");
        $form->addButton("Multijoueur");
        $form->sendToPlayer($player);
        return $form;
    }

    public function multi($player){
        $api = Server::getInstance()->getPluginManager()->getPlugin("FormAPI");
        $form = $api->createSimpleForm(function (Player $player, int $data = null){
            $result = $data;
            if ($result === null){
                return false;
            }
            switch ($result){
                case 0:
                    Server::getInstance()->dispatchCommand($player, "laserjoin10vs10 red");
                    break;
                case 1:
                    Server::getInstance()->dispatchCommand($player, "laserjoin10vs10 blue");
                    break;
                case 2:
                    $this->laserui($player);
                    break;
            }
        });
        $form->setTitle("LaserGame MultiJoueur");
        $form->setContent("Choisie l'équipe que tu veux:");
        $form->addButton("§4Rouge");
        $form->addButton("§1Bleu");
        $form->addButton("§l§cRetour");
        $form->sendToPlayer($player);
        return $form;
    }
}