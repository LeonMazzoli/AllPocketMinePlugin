<?php

namespace Digueloulou12\Forms;

use Digueloulou12\Events\ChatEvent;
use Digueloulou12\Main;
use jojoe77777\FormAPI\SimpleForm;
use pocketmine\Player;

class DiversForms
{
    public static function listPlayerMute(Player $player)
    {
        $form = new SimpleForm(function (Player $player, $data = null) {
            if ($data === null) return;

            if (empty(ChatEvent::$mute[$data])) {
                $player->sendMessage(Main::getConfigAPI()->getConfigValue("unmute_nomute"));
                return;
            }

            $player->sendMessage(Main::getConfigAPI()->getConfigValue("mute_msg", ["{player}", "{reason}", "{modo}"], [$data, ChatEvent::$mute[$data]["reason"], ChatEvent::$mute[$data]["modo"]]));
        });
        $form->setTitle(Main::getConfigAPI()->getConfigValue("mute_form_title"));
        $form->setContent(Main::getConfigAPI()->getConfigValue("mute_form_content"));
        foreach (ChatEvent::$mute as $name => $key) {
            if ($key["time"] >= time()) {
                $form->addButton(Main::getConfigAPI()->getConfigValue("mute_form_button", ["{name}"], [$name]), -1, "", $name);
            }
        }
        $form->sendToPlayer($player);
    }
}