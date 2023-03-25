<?php

namespace Digueloulou12;

use jojoe77777\FormAPI\CustomForm;
use pocketmine\Player;
use pocketmine\Server;

class DuelForms
{
    public static function form(Player $player): CustomForm
    {
        $kit = [];
        foreach (MainDuel::$config->get("kit") as $kit_ => $key) {
            $kit[] = $kit_;
        }

        $players = [];
        foreach (Server::getInstance()->getOnlinePlayers() as $pp) {
            $players[] = $pp;
        }
        $form = new CustomForm(function (Player $player, array $data = null) use ($kit, $players){
            $money = Server::getInstance()->getPluginManager()->getPlugin("EconomyAPI");
            if ($data === null) return;

            $sender = Server::getInstance()->getPlayer($data[0]);
            if ($sender instanceof Player) {
                if (count(DuelAPI::$players) !== 2) {
                    if ($sender->getName() !== $player->getName()) {
                        if ($money->myMoney($player) >= $data[2]) {
                            if ($money->myMoney($sender) >= $data[2]) {
                                DuelAPI::sendInvitation($player, $sender, $kit[$data[1]], $data[2]);
                            } else $player->sendMessage(MainDuel::$config->get("no_money_sender"));
                        } else $player->sendMessage(MainDuel::$config->get("no_money"));
                    } else $player->sendMessage(MainDuel::$config->get("no"));
                } else $player->sendMessage(MainDuel::$config->get("already"));
            } else $player->sendMessage(MainDuel::$config->get("offline"));
        });
        $form->setTitle(MainDuel::$config->get("title"));
        $form->addDropdown(MainDuel::$config->get("dropdown"), $players);
        $form->addDropdown(MainDuel::$config->get("content"), $kit);
        $form->addSlider(MainDuel::$config->get("slider"), MainDuel::$config->get("min"), MainDuel::$config->get("max"));
        $form->sendToPlayer($player);
        return $form;
    }
}