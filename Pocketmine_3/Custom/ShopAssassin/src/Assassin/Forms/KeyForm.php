<?php

namespace Assassin\Forms;

use pocketmine\command\ConsoleCommandSender;
use pocketmine\Player;
use pocketmine\Server;

class KeyForm{
    public static function KeyForm(Player $player){
        $eco = Server::getInstance()->getPluginManager()->getPlugin("EconomyAPI");
        $api = Server::getInstance()->getPluginManager()->getPlugin("FormAPI");
        $form = $api->createSimpleForm(function (Player $player, int $data = null) use ($eco){
            $result = $data;
            if ($result === null){
                return false;
            }
            switch ($result){
                case 0:
                    if ($eco->myMoney($player) >= 20){
                        Server::getInstance()->dispatchCommand(new ConsoleCommandSender(), 'givekey "' . $player->getName() . '" vote 1');
                        $eco->reduceMoney($player, 20);
                    }else{
                        $player->sendMessage("Vous n'avez pas de money !");
                    }
                    break;
                case 1:
                    if ($eco->myMoney($player) >= 75){
                        Server::getInstance()->dispatchCommand(new ConsoleCommandSender(), 'givekey "' . $player->getName() . '" master 1');
                        $eco->reduceMoney($player, 75);
                    }else{
                        $player->sendMessage("Vous n'avez pas de money !");
                    }
                    break;
                case 2:
                    if ($eco->myMoney($player) >= 150){
                        Server::getInstance()->dispatchCommand(new ConsoleCommandSender(), 'givekey "' . $player->getName() . '" assassin 1');
                        $eco->reduceMoney($player, 150);
                    }else{
                        $player->sendMessage("Vous n'avez pas de money !");
                    }
                    break;
                case 3:
                    if ($eco->myMoney($player) >= 200){
                        Server::getInstance()->dispatchCommand(new ConsoleCommandSender(), 'givekey "' . $player->getName() . '" hope 1');
                        $eco->reduceMoney($player, 200);
                    }else{
                        $player->sendMessage("Vous n'avez pas de money !");
                    }
                    break;
                case 4:
                    ShopForm::ShopForm($player);
                    break;
            }
        });
        $form->setTitle("§cBoutique");
        $form->setContent("Choisi la clef que tu veux acheté:");
        if ($eco->myMoney($player) <= 20){$form->addButton("§cVote\n20");}else{$form->addButton("§bVote\n20");}
        if ($eco->myMoney($player) <= 75){$form->addButton("§cMaster\n75");}else{$form->addButton("§bMaster\n75");}
        if ($eco->myMoney($player) <= 150){$form->addButton("§cAssassin\n150");}else{$form->addButton("§bAssassin\n150");}
        if ($eco->myMoney($player) <= 200){$form->addButton("§cHope\n200");}else{$form->addButton("§bHope\n200");}
        $form->addButton("§l§cRetour");
        $form->sendToPlayer($player);
        return $form;
    }
}