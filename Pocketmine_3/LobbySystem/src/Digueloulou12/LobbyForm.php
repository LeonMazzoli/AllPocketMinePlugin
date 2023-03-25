<?php

namespace Digueloulou12;

use jojoe77777\FormAPI\SimpleForm;
use pocketmine\Player;
use pocketmine\utils\Config;

class LobbyForm
{
    public static function form(Player $player)
    {
        $config = new Config(LobbyMain::getInstance()->getDataFolder() . "config.yml", Config::YAML);
        $form = new SimpleForm(function (Player $player, $data = null) use ($config) {
            if ($data === null) return;
            $ip = explode(":", $config->get("servers")[$data]["ip"]);
            $player->transfer($ip[0], $ip[1]);
        });
        $form->setTitle(LobbyMain::getConfigValue("title"));
        $form->setContent(LobbyMain::getConfigValue("content"));
        foreach (LobbyMain::getConfigValue("servers") as $servers => $key) {
            $form->addButton($key["form"], -1, "", $servers);
        }
        $form->sendToPlayer($player);
        return $form;
    }
}