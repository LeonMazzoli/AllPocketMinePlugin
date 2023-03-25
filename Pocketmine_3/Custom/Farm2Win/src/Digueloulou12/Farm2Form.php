<?php

namespace Digueloulou12;

use jojoe77777\FormAPI\SimpleForm;
use pocketmine\command\ConsoleCommandSender;
use pocketmine\Player;
use pocketmine\Server;

class Farm2Form{
    public static function form(Player $player){
        $config = Farm2Command::$config;
        $ecoapi = Server::getInstance()->getPluginManager()->getPlugin("EconomyAPI");
        $form = new SimpleForm(function (Player $player, int $data = null) use ($config, $ecoapi){
            if ($data === null){
                return true;
            }
            switch ($data){
                case 0:
                    $grade1 = $config->get("grades")[0];
                    $info = explode(":", $grade1);
                    if ($ecoapi->myMoney($player) >= $info[1]){
                        $ecoapi->reduceMoney($player, $info[1]);
                        $player->sendMessage(str_replace(strtolower('{grade}'), $info[0], $config->get("good")));
                        Server::getInstance()->getCommandMap()->dispatch(new ConsoleCommandSender(), str_replace([strtolower('{player}'), strtolower('{grade}')], [$player->getName(), $info[0]], $config->get("command")));
                    }else $player->sendMessage($config->get("no_money"));
                    break;
                case 1:
                    $grade2 = $config->get("grades")[1];
                    $info2 = explode(":", $grade2);
                    if ($ecoapi->myMoney($player) >= $info2[1]){
                        $ecoapi->reduceMoney($player, $info2[1]);
                        $player->sendMessage(str_replace(strtolower('{grade}'), $info2[0], $config->get("good")));
                        Server::getInstance()->getCommandMap()->dispatch(new ConsoleCommandSender(), str_replace([strtolower('{player}'), strtolower('{grade}')], [$player->getName(), $info2[0]], $config->get("command")));
                    }else $player->sendMessage($config->get("no_money"));
                    break;
                case 2:
                    $grade3 = $config->get("grades")[2];
                    $info3 = explode(":", $grade3);
                    if ($ecoapi->myMoney($player) >= $info3[1]){
                        $ecoapi->reduceMoney($player, $info3[1]);
                        $player->sendMessage(str_replace(strtolower('{grade}'), $info3[0], $config->get("good")));
                        Server::getInstance()->getCommandMap()->dispatch(new ConsoleCommandSender(), str_replace([strtolower('{player}'), strtolower('{grade}')], [$player->getName(), $info3[0]], $config->get("command")));
                    }else $player->sendMessage($config->get("no_money"));
                    break;
            }
            return true;
        });
        $form->setTitle($config->get("title"));
        $form->setContent($config->get("content"));
        $form->addButton($config->get("1"));
        $form->addButton($config->get("2"));
        $form->addButton($config->get("3"));
        $form->sendToPlayer($player);
        return $form;
    }
}