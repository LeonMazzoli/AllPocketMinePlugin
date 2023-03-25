<?php

namespace Digueloulou12;

use jojoe77777\FormAPI\SimpleForm;
use pocketmine\player\Player;
use pocketmine\Server;

class DuelForms
{
    public static function mainForm(Player $player, int $money): SimpleForm
    {
        $form = new SimpleForm(function (Player $player, int $data = null) use ($money) {
            if ($data === null) return;
            switch ($data) {
                case 0:
                    if (count(DuelAPI::$players) === 2) {
                        $player->sendMessage(MainDuel::$config->get("already"));
                        return;
                    }

                    $players = Server::getInstance()->getOnlinePlayers();
                    $random = $players[array_rand($players)];

                    if ($random->getName() === $player->getName()) {
                        $player->sendMessage(MainDuel::$config->get("no"));
                        return;
                    }

                    if (Server::getInstance()->getPluginManager()->getPlugin("EconomyAPI")->myMoney($random) >= $money) {
                        DuelAPI::sendInvitation($random, $player, $money);
                        $player->sendMessage(str_replace(["{player}", "{money}"], [$random->getName(), $money], MainDuel::$config->get("invite")));
                    } else $player->sendMessage(MainDuel::$config->get("no_money_"));
                    break;
                case 1:
                    if (count(DuelAPI::$players) === 2) {
                        $player->sendMessage(MainDuel::$config->get("already"));
                        return;
                    }

                    self::listPlayer($player, $money);
                    break;
            }
        });
        $form->setTitle(MainDuel::$config->get("title"));
        $form->addButton(MainDuel::$config->get("random"));
        $form->addButton(MainDuel::$config->get("choose"));
        $form->sendToPlayer($player);
        return $form;
    }

    public static function listPlayer(Player $player, int $money): SimpleForm
    {
        $form = new SimpleForm(function (Player $player, $data = null) use ($money) {
            if ($data === null) return self::mainForm($player, $money);

            if (count(DuelAPI::$players) === 2) {
                $player->sendMessage(MainDuel::$config->get("already"));
                return true;
            }

            if (Server::getInstance()->getPlayerByPrefix($data) !== null) {
                $sender = Server::getInstance()->getPlayerByPrefix($data);
                if (Server::getInstance()->getPluginManager()->getPlugin("EconomyAPI")->myMoney($sender) >= $money) {
                    DuelAPI::sendInvitation($sender, $player, $money);
                    $player->sendMessage(str_replace(["{player}", "{money}"], [$sender->getName(), $money], MainDuel::$config->get("invite")));
                } else $player->sendMessage(MainDuel::$config->get("no_money_"));
            } else $player->sendMessage(MainDuel::$config->get("offline2"));
            return true;
        });
        $form->setTitle(MainDuel::$config->get("title"));
        foreach (Server::getInstance()->getOnlinePlayers() as $sender) {
            if ($sender->getName() !== $player->getName()) {
                $form->addButton($sender->getName(), -1, "", $sender->getName());
            }
        }
        $form->sendToPlayer($player);
        return $form;
    }
}