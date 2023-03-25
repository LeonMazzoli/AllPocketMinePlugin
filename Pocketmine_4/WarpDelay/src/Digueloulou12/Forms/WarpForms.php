<?php

namespace Digueloulou12\Forms;

use jojoe77777\FormAPI\SimpleForm;
use Digueloulou12\API\WarpAPI;
use pocketmine\player\Player;
use Digueloulou12\WarpDelay;
use pocketmine\Server;

class WarpForms
{
    public static function warpForm(): SimpleForm
    {
        $form = new SimpleForm(function (Player $player, string $data = null) {
            if ($data === null) return;

            $name = explode(":", WarpDelay::getConfigValue("warp_cmd"))[0];
            Server::getInstance()->getCommandMap()->dispatch($player, "$name $data");
        });
        $form->setTitle(WarpDelay::getConfigValue("title"));
        $form->setContent(WarpDelay::getConfigValue("content"));
        foreach (WarpAPI::getAllWarps() as $warp) {
            $form->addButton(WarpDelay::getConfigReplace("button", "{warp}", $warp), -1, "", $warp);
        }
        return $form;
    }
}