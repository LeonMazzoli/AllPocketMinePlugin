<?php

namespace Digueloulou12\Forms;

use Digueloulou12\API\WarpAPI;
use Digueloulou12\Main;
use jojoe77777\FormAPI\CustomForm;
use jojoe77777\FormAPI\SimpleForm;
use pocketmine\Player;

class WarpForms
{
    public static function formMain(Player $player)
    {
        $form = new SimpleForm(function (Player $player, $data = null) {
            if ($data === null) return;

            WarpAPI::teleportToWarp($player, $data);
        });
        $form->setTitle(Main::getConfigAPI()->getConfigValue("warp_title"));
        foreach (WarpAPI::listWarps() as $warp){
            $form->addButton(Main::getConfigAPI()->getConfigValue("warp_button", ["{warp}"], [$warp]), -1, "", $warp);
        }
        $form->sendToPlayer($player);
        return $form;
    }

    public static function adminForm(Player $player)
    {
        $form = new SimpleForm(function (Player $player, int $data = null) {
            if ($data === null) return;

            switch ($data) {
                case 0:
                    self::manageWarp($player);
                    break;
                case 1:
                    self::formMain($player);
                    break;
                case 2:
                    self::manageWarp($player, "remove");
                    break;
            }
        });
        $form->setTitle(Main::getConfigAPI()->getConfigValue("warp_title_admin"));
        $form->addButton("AddWarp");
        $form->addButton("ListWarp");
        $form->addButton("RemoveWarp");
        $form->sendToPlayer($player);
        return $form;
    }

    public static function manageWarp(Player $player, string $type = "add")
    {
        $form = new CustomForm(function (Player $player, array $data = null) use ($type) {
            if ($data === null) {
                self::adminForm($player);
                return;
            }

            if ($type === "add") {
                if (!WarpAPI::existWarp($data[0])) {
                    WarpAPI::addWarp($player, $data[0]);
                    $player->sendMessage(Main::getConfigAPI()->getConfigValue("warp_add", ["{warp}"], [$data[0]]));
                } else $player->sendMessage(Main::getConfigAPI()->getConfigValue("warp_already", ["{warp}"], [$data[0]]));
            } else {
                if (WarpAPI::existWarp($data[0])) {
                    WarpAPI::removeWarp($data[0]);
                    $player->sendMessage(Main::getConfigAPI()->getConfigValue("warp_removewarp", ["{warp}"], [$data[0]]));
                } else $player->sendMessage(Main::getConfigAPI()->getConfigValue("warp_noexist", ["{warp}"], [$data[0]]));
            }
        });
        $form->setTitle(Main::getConfigAPI()->getConfigValue("warp_title_admin"));
        $form->addInput(Main::getConfigAPI()->getConfigValue("warp_label_manage"));
        $form->sendToPlayer($player);
        return $form;
    }
}