<?php

namespace Digueloulou12\Forms;

use Digueloulou12\Main;
use jojoe77777\FormAPI\SimpleForm;
use pocketmine\item\Armor;
use pocketmine\item\Tool;
use pocketmine\Player;

class AnvilForms
{
    public static function formMain(Player $player)
    {
        $form = new SimpleForm(function (Player $player, int $data = null){
            if ($data === null) return;

            $item = $player->getInventory()->getItemInHand();
            if ($item->getId() === 0) {
                $player->sendMessage(Main::getConfigAPI()->getConfigValue("anvil_no"));
                return;
            }

            switch ($data) {
                case 0:

                    break;
                case 1:
                    if (($item instanceof Tool) or ($item instanceof Armor)) {
                        if ($item->getDamage() !== 0) {

                        } else $player->sendMessage();
                    } else $player->sendMessage();
                    break;
            }
        });
        $form->setTitle(Main::getConfigAPI()->getConfigValue("anvil_title"));
        $form->addButton(Main::getConfigAPI()->getConfigValue("anvil_buton_rename"));
        $form->addButton(Main::getConfigAPI()->getConfigValue("anvil_button_repair"));
        $form->sendToPlayer($player);
        return $form;
    }
}