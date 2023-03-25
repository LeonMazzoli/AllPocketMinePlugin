<?php

namespace Digueloulou12\Drawer\Forms;

use Digueloulou12\Drawer\API\Drawer;
use Digueloulou12\Drawer\Utils\Utils;
use jojoe77777\FormAPI\CustomForm;
use pocketmine\player\Player;

class DrawerForm
{
    public static function drawerForm(Drawer $drawer): CustomForm
    {
        $form = new CustomForm(function (Player $player, array $data = null) use ($drawer) {
            if ($data === null) return;

            if ($drawer->getItem()->getCount() >= $data[1]) {
                if ($player->getInventory()->canAddItem($drawer->getItem()->setCount($data[1]))) {
                    $drawer->removeItem($drawer->getItem()->setCount($data[1]));
                    $player->getInventory()->addItem($drawer->getItem()->setCount($data[1]));
                    $player->sendMessage(Utils::getConfigReplace("pickup_item", "{count}", $data[1]));
                } else $player->sendMessage(Utils::getConfigReplace("no_place_inv"));
            } else $player->sendMessage(Utils::getConfigReplace("no_item_in"));
        });
        $form->setTitle(Utils::getConfigReplace("title"));
        $form->addLabel(Utils::getConfigReplace("content", "{count}", $drawer->getItem()->getCount()));
        $form->addSlider(Utils::getConfigReplace("slider"), 0, $drawer->getItem()->getCount());
        return $form;
    }
}