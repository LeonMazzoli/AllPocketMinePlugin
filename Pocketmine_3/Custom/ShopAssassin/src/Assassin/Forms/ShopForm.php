<?php

namespace Assassin\Forms;

use pocketmine\Player;
use pocketmine\Server;

class ShopForm{
    public static function ShopForm(Player $player){
        $api = Server::getInstance()->getPluginManager()->getPlugin("FormAPI");
        $form = $api->createSimpleForm(function (Player $player, int $data = null){
            $result = $data;
            if ($result === null){
                return false;
            }
            switch ($result){
                case 0:
                    KeyForm::KeyForm($player);
                    break;
                case 1:
                    CapeForm::CapeForm($player);
                    break;
                case 2:
                    GradeForm::GradeForm($player);
                    break;
                case 3:
                    KillEffectForm::KillEffectForm($player);
                    break;
            }
        });
        $form->setTitle("§bBoutique");
        $form->setContent("Choisi ce que tu désire:");
        $form->addButton("Clefs");
        $form->addButton("Capes");
        $form->addButton("Grades");
        $form->addButton("Kill effet");
        $form->sendToPlayer($player);
        return $form;
    }
}