<?php

namespace Report\Forms;

use jojoe77777\FormAPI\CustomForm;
use pocketmine\Player;
use pocketmine\Server;
use pocketmine\utils\Config;
use Report\API\DiscordAPI;
use Report\Commands\Report;
use Report\ReportMain;

class ReportForm{
    public static function report(Player $player){
        $dataa = new Config(ReportMain::getInstance()->getDataFolder()."data.json",Config::JSON);
        $form = new CustomForm(function (Player $player, array $data = null) use ($dataa) {
            if ($data === null) return;
            if (!isset($data[1])){
                $player->sendMessage(ReportMain::getInstance()->getConfigValue("no_title"));
                return;
            }

            if (!isset($data[3])){
                $player->sendMessage(ReportMain::getInstance()->getConfigValue("no_desc"));
                return;
            }

            if ($dataa->getNested("reports.$data[1]") !== null){
                $player->sendMessage(ReportMain::getInstance()->getConfigValue("reportal"));
                return;
            }

            $members = [];
            foreach (Server::getInstance()->getOnlinePlayers() as $member) {
                $members[] = $member->getName();
            }
            $sender = Server::getInstance()->getPlayer($members[$data[2]]);

            $info = ["title" => $data[1], "player" => $members[$data[2]], "report" => $player->getName(), "description" => $data[3]];
            $dataa->setNested("reports.$data[1]", $info);
            $dataa->save();

            // Alert
            if (ReportMain::getInstance()->getConfigValue("alert") === true){
                foreach (Server::getInstance()->getOnlinePlayers() as $senderr){
                    if ($senderr->hasPermission(ReportMain::getInstance()->getConfigValue("permission"))){
                        $senderr->sendMessage(str_replace(strtolower('{name}'), $player->getName(), ReportMain::getInstance()->getConfigValue("message")));
                    }
                }
            }

            // Alert Player Report
            if (ReportMain::getInstance()->getConfigValue("alerts") === true){
                if ($sender instanceof Player){
                    $sender->sendMessage(str_replace(strtolower("{player}"), $player->getName(), ReportMain::getInstance()->getConfigValue("messager")));
                }
            }

            // Discord
            if (ReportMain::getInstance()->getConfigValue("discord") === true){
                if (ReportMain::getInstance()->getConfigValue("webhook") !== "") {
                    if (ReportMain::getInstance()->getConfigValue("type") === "message") {
                        DiscordAPI::sendMessage(str_replace([strtolower("{player}"), strtolower("{sender}"), strtolower('{desc}')], [$player->getName(), $members[$data[2]], $data[3]], ReportMain::getInstance()->getConfigValue("messaged")));
                    }else DiscordAPI::sendEmbed(str_replace([strtolower("{player}"), strtolower("{sender}"), strtolower(" {desc}")], [$player->getName(), $members[$data[2]], $data[3]], ReportMain::getInstance()->getConfigValue("content_embed")));
                }
            }
        });
        $players = [];
        foreach (Server::getInstance()->getOnlinePlayers() as $sender){
            $players[] = $sender->getName();
        }
        $form->setTitle(ReportMain::getInstance()->getConfigValue("title"));
        $form->addLabel(ReportMain::getInstance()->getConfigValue("label"));
        $form->addInput(ReportMain::getInstance()->getConfigValue("titler"));
        $form->addDropdown(ReportMain::getInstance()->getConfigValue("player"), $players);
        $form->addInput(ReportMain::getInstance()->getConfigValue("description"));
        $form->sendToPlayer($player);
        return $form;
    }
}