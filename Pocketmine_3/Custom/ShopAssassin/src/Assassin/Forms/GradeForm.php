<?php

namespace Assassin\Forms;

use pocketmine\command\ConsoleCommandSender;
use pocketmine\Player;
use pocketmine\Server;

class GradeForm{
    public static function GradeForm(Player $player){
        $eco = Server::getInstance()->getPluginManager()->getPlugin("EconomyAPI");
        $api = Server::getInstance()->getPluginManager()->getPlugin("FormAPI");
        $form = $api->createSimpleForm(function (Player $player, int $data = null) use ($eco){
            $result = $data;
            if ($result === null){
                return false;
            }
            switch ($result){
                case 0:
                    if ($eco->myMoney($player) >= 250){
                        Server::getInstance()->dispatchCommand(new ConsoleCommandSender(), 'setgroup "' . $player->getName() . '" vip');
                        $eco->reduceMoney($player, 250);
                    }else{
                        $player->sendMessage("Vous n'avez pas de money !");
                    }
                    break;
                case 1:
                    if ($eco->myMoney($player) >= 500){
                        Server::getInstance()->dispatchCommand(new ConsoleCommandSender(), 'setgroup "' . $player->getName() . '" vipplus');
                        $eco->reduceMoney($player, 500);
                    }else{
                        $player->sendMessage("Vous n'avez pas de money !");
                    }
                    break;
                case 2:
                    if ($eco->myMoney($player) >= 750){
                        Server::getInstance()->dispatchCommand(new ConsoleCommandSender(), 'setgroup "' . $player->getName() . '" tatar');
                        $eco->reduceMoney($player, 750);
                    }else{
                        $player->sendMessage("Vous n'avez pas de money !");
                    }
                    break;
                case 3:
                    ShopForm::ShopForm($player);
                    break;
            }
        });
        $form->setTitle("§cBoutique");
        $form->setContent("Choisi la clef que tu veux acheté:");
        if ($eco->myMoney($player) <= 250){$form->addButton("§cVIP\n250");}else{$form->addButton("§bVIP\n250");}
        if ($eco->myMoney($player) <= 500){$form->addButton("§cVIP+\n500");}else{$form->addButton("§bVIP+\n500");}
        if ($eco->myMoney($player) <= 750){$form->addButton("§cTATAR\n750");}else{$form->addButton("§bTATAR\n750");}
        $form->addButton("§l§cRetour");
        $form->sendToPlayer($player);
        return $form;
    }
}