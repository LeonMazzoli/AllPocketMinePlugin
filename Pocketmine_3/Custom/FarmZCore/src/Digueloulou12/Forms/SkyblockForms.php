<?php

namespace Digueloulou12\Forms;

use Digueloulou12\API\MoneyAPI;
use Digueloulou12\API\SkyblockAPI;
use Digueloulou12\Main;
use jojoe77777\FormAPI\CustomForm;
use jojoe77777\FormAPI\SimpleForm;
use pocketmine\Player;
use pocketmine\Server;

class SkyblockForms
{
    public static function mainForm(Player $player)
    {
        $form = new SimpleForm(function (Player $player, int $data = null) {
            if ($data === null) return;

            switch ($data) {
                case 0:
                    if (!SkyblockAPI::isInIsland($player)) {
                        self::createIsland($player);
                    } else $player->sendMessage(Main::getConfigAPI()->getConfigValue("is_already_in_is"));
                    break;
                case 1:
                    if (SkyblockAPI::isInIsland($player)) {
                        $player->teleport(SkyblockAPI::getSpawnIsland(SkyblockAPI::getIslandPlayer($player)));
                        $player->sendMessage(Main::getConfigAPI()->getConfigValue("is_tpgood"));
                    } else $player->sendMessage(Main::getConfigAPI()->getConfigValue("is_no_island_msg"));
                    break;
                case 2:

                    break;
                case 3:
                    if (SkyblockAPI::isInIsland($player)) {
                        self::bank($player);
                    } else $player->sendMessage(Main::getConfigAPI()->getConfigValue("is_no_island_msg"));
                    break;
                case 4:
                    if (SkyblockAPI::isInIsland($player)) {
                        if (SkyblockAPI::getOwnerPlayer(SkyblockAPI::getIslandPlayer($player)) === $player->getName()) {
                            SkyblockAPI::disbandIsland(SkyblockAPI::getIslandPlayer($player));
                            $player->sendMessage(Main::getConfigAPI()->getConfigValue("is_disband_msg"));
                        } else $player->sendMessage(Main::getConfigAPI()->getConfigValue("is_noowner_msg"));
                    } else $player->sendMessage(Main::getConfigAPI()->getConfigValue("is_no_island_msg"));
                    break;
            }
        });
        $form->setTitle(Main::getConfigAPI()->getConfigValue("is_title"));
        $form->addButton(Main::getConfigAPI()->getConfigValue("is_button_create"));
        $form->addButton(Main::getConfigAPI()->getConfigValue("is_button_go"));
        $form->addButton(Main::getConfigAPI()->getConfigValue("is_button_members"));
        $form->addButton(Main::getConfigAPI()->getConfigValue("is_button_bank"));
        $form->addButton(Main::getConfigAPI()->getConfigValue("is_button_del"));
        $form->sendToPlayer($player);
        return $form;
    }

    public static function createIsland(Player $player)
    {
        $form = new CustomForm(function (Player $player, array $data = null) {
            if ($data === null) {
                self::mainForm($player);
                return;
            }

            if (strlen($data[1]) <= 3) {
                $player->sendMessage(Main::getConfigAPI()->getConfigValue("is_no_valid_title"));
                return;
            }

            if (strlen($data[1]) >= 13) {
                $player->sendMessage(Main::getConfigAPI()->getConfigValue("is_no_valid_title"));
                return;
            }

            if (Server::getInstance()->getLevelByName($data[1]) === null) {
                SkyblockAPI::createIsland($player, $data[1]);
                $player->sendMessage(Main::getConfigAPI()->getConfigValue("is_create_msg"));
            } else $player->sendMessage(Main::getConfigAPI()->getConfigValue("is_already_exist"));
        });
        $form->setTitle(Main::getConfigAPI()->getConfigValue("is_title"));
        $form->addLabel(Main::getConfigAPI()->getConfigValue("is_label_create"));
        $form->addInput(Main::getConfigAPI()->getConfigValue("is_input_create"));
        $form->sendToPlayer($player);
        return $form;
    }

    public static function bank(Player $player)
    {
        $form = new SimpleForm(function (Player $player, int $data = null) {
            if ($data === null) return;

            switch ($data) {
                case 0:
                    self::manageBank($player);
                    break;
                case 1:
                    if (SkyblockAPI::isInIsland($player)) {
                        $player->sendMessage(Main::getConfigAPI()->getConfigValue("is_bank_status", [strtolower("{money}")], [SkyblockAPI::islandBank(SkyblockAPI::getIslandPlayer($player))]));
                    } else $player->sendMessage(Main::getConfigAPI()->getConfigValue("is_no_island_msg"));
                    break;
                case 2:
                    self::manageBank($player, "remove");
                    break;
            }
        });
        $form->setTitle(Main::getConfigAPI()->getConfigValue("is_title"));
        $form->addButton(Main::getConfigAPI()->getConfigValue("is_button_add"));
        $form->addButton(Main::getConfigAPI()->getConfigValue("is_button_get"));
        $form->addButton(Main::getConfigAPI()->getConfigValue("is_button_remove"));
        $form->sendToPlayer($player);
        return $form;
    }

    public static function manageBank(Player $player, $type = "add")
    {
        $form = new CustomForm(function (Player $player, array $data = null) use ($type) {
            if ($data === null) return;

            if (!is_numeric($data[0])) {
                $player->sendMessage(Main::getConfigAPI()->getConfigValue("is_numeric"));
                return;
            }

            if ($data[0] <= 0) {
                $player->sendMessage(Main::getConfigAPI()->getConfigValue("no_negative_value"));
                return;
            }

            if ($type === "add") {
                if (MoneyAPI::getMoney($player) >= $data[0]) {
                    MoneyAPI::removeMoney($player, $data[0]);
                    SkyblockAPI::islandBank(SkyblockAPI::getIslandPlayer($player), $data[0], "add");
                    $player->sendMessage(Main::getConfigAPI()->getConfigValue("is_bank_deposit", [strtolower("{money}")], [$data[0]]));
                } else $player->sendMessage(Main::getConfigAPI()->getConfigValue("pay_nomoney_msg"));
            } else {
                if (SkyblockAPI::islandBank(SkyblockAPI::getIslandPlayer($player)) >= $data[0]) {
                    if (SkyblockAPI::getRankPlayer($player) !== "Membre") {
                        MoneyAPI::addMoney($player, $data[0]);
                        SkyblockAPI::islandBank(SkyblockAPI::getIslandPlayer($player), $data[0], "remove");
                        $player->sendMessage(Main::getConfigAPI()->getConfigValue("is_bank_withdraw", [strtolower("{money}")], [$data[0]]));
                    } else $player->sendMessage(Main::getConfigAPI()->getConfigValue("is_officier_requis"));
                } else $player->sendMessage(Main::getConfigAPI()->getConfigValue("is_bank_nomoney"));
            }
        });
        $form->setTitle(Main::getConfigAPI()->getConfigValue("is_title"));
        $form->addInput(Main::getConfigAPI()->getConfigValue("is_input_bank"));
        $form->sendToPlayer($player);
        return $form;
    }
}