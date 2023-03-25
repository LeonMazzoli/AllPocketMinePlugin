<?php

namespace Assassin\Forms;

use pocketmine\Player;
use pocketmine\Server;

class KillEffectForm{
    public static function KillEffectForm(Player $player){
        $eco = Server::getInstance()->getPluginManager()->getPlugin("EconomyAPI");
        $api = Server::getInstance()->getPluginManager()->getPlugin("FormAPI");
        $form = $api->createSimpleForm(function (Player $player, int $data = null) use ($eco){
            $result = $data;
            if ($result === null){
                return false;
            }
            switch ($result){
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