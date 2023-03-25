<?php

namespace Digueloulou12;

use jojoe77777\FormAPI\SimpleForm;
use pocketmine\player\Player;
use pocketmine\Server;

class DuelForms
{
    public static function mainForm(Player $player): SimpleForm
    {
        $form = new SimpleForm(function (Player $player, int $data = null) {
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

                    DuelAPI::sendInvitation($random, $player);
                    $player->sendMessage(str_replace(strtolower("{player}"), $random->getName(), MainDuel::$config->get("invite")));
                    break;
                case 1:
                    if (count(DuelAPI::$players) === 2) {
                        $player->sendMessage(MainDuel::$config->get("already"));
                        return;
                    }

                    self::listPlayer($player);
                    break;
            }
        });
        $form->setTitle(MainDuel::$config->get("title"));
        $form->addButton(MainDuel::$config->get("random"));
        $form->addButton(MainDuel::$config->get("choose"));
        $form->sendToPlayer($player);
        return $form;
    }

    public static function listPlayer(Player $player): SimpleForm
    {
        $form = new SimpleForm(function (Player $player, $data = null) {
            if ($data === null) return self::mainForm($player);

            if (count(DuelAPI::$players) === 2) {
                $player->sendMessage(MainDuel::$config->get("already"));
                return true;
            }

            if (Server::getInstance()->getPlayerByPrefix($data) !== null) {
                $sender = Server::getInstance()->getPlayerByPrefix($data);
                DuelAPI::sendInvitation($sender, $player);
                $player->sendMessage(str_replace(strtolower("{player}"), $sender->getName(), MainDuel::$config->get("invite")));
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