<?php

namespace THS\Forms;

use jojoe77777\FormAPI\SimpleForm;
use pocketmine\level\Position;
use pocketmine\Player;
use pocketmine\Server;

class ReunionForm{
    public static function form(Player $player){
        $form = new SimpleForm(function (Player $player, int $data = null){
            if ($data === null) return;
            switch ($data){
                case 0:
                    $player->teleport(new Position(947, 8, 1001, Server::getInstance()->getLevelByName("Hub")));
                    break;
                case 1:
                    $player->teleport(new Position(950, 8, 999, Server::getInstance()->getLevelByName("Hub")));
                    break;
                case 2:
                    $player->teleport(new Position(944, 8, 999, Server::getInstance()->getLevelByName("Hub")));
                    break;
                case 3:
                    $player->teleport(new Position(949, 8, 997, Server::getInstance()->getLevelByName("Hub")));
                    break;
                case 4:
                    $player->teleport(new Position(944, 8, 997, Server::getInstance()->getLevelByName("Hub")));
                    break;
                case 5:
                    $player->teleport(new Position(949, 8, 995, Server::getInstance()->getLevelByName("Hub")));
                    break;
                case 6:
                    $player->teleport(new Position(945, 8, 995, Server::getInstance()->getLevelByName("Hub")));
                    break;
                case 7:
                    $player->teleport(new Position(949, 8, 993, Server::getInstance()->getLevelByName("Hub")));
                    break;
                case 8:
                    $player->teleport(new Position(944, 8, 993, Server::getInstance()->getLevelByName("Hub")));
                    break;
                case 9:
                    $player->teleport(new Position(944, 8, 991, Server::getInstance()->getLevelByName("Hub")));
                    break;
                case 10:
                    $player->teleport(new Position(950, 8, 991, Server::getInstance()->getLevelByName("Hub")));
                    break;
                case 11:
                    $player->teleport(new Position(950, 8, 989, Server::getInstance()->getLevelByName("Hub")));
                    break;
                case 12:
                    $player->teleport(new Position(944, 8, 989, Server::getInstance()->getLevelByName("Hub")));
                    break;
                case 13:
                    $player->teleport(new Position(949, 8, 987, Server::getInstance()->getLevelByName("Hub")));
                    break;
                case 14:
                    $player->teleport(new Position(944, 8, 987, Server::getInstance()->getLevelByName("Hub")));
                    break;
            }
        });
        $form->setTitle("§a- §fRéunion §f-");
        $form->setContent("Prener place sur votre fauteuil:");
        $form->addButton("§cFondateur");
        $form->addButton("§4Administrateur");
        $form->addButton("§2Digueloulou12");
        $form->addButton("§eManager");
        $form->addButton("§dResponsable");
        $form->addButton("§1Super-Modo1");
        $form->addButton("§1Super-Modo2");
        $form->addButton("§aModerateur1");
        $form->addButton("§aModérateur2");
        $form->addButton("§aModérateur3");
        $form->addButton("§bGuide1");
        $form->addButton("§bGuide2");
        $form->addButton("§bGuide3");
        $form->addButton("§bGuide4");
        $form->addButton("§bGuide5");
        $form->addButton("§l§cRetour");
        $form->sendToPlayer($player);
        return $form;
    }
}