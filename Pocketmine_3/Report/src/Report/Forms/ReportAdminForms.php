<?php

namespace Report\Forms;

use jojoe77777\FormAPI\SimpleForm;
use pocketmine\Player;
use pocketmine\Server;
use pocketmine\utils\Config;
use Report\API\DiscordAPI;
use Report\ReportMain;

class ReportAdminForms
{
    public static function reportAdmin(Player $player)
    {
        $dataa = new Config(ReportMain::getInstance()->getDataFolder() . "data.json", Config::JSON);
        $form = new SimpleForm(function (Player $player, $data = null) use ($dataa) {
            if ($data === null) return;
            self::report($player, $data);
        });
        $form->setTitle(ReportMain::getInstance()->getConfigValue("titlea"));
        $form->setContent(ReportMain::getInstance()->getConfigValue("content"));
        foreach ($dataa->getAll()["reports"] as $reports => $keys) {
            $form->addButton($keys["title"], -1, "", $keys["title"]);
        }
        $form->sendToPlayer($player);
        return $form;
    }



    public static function report(Player $player, $report)
    {
        $dataa = new Config(ReportMain::getInstance()->getDataFolder() . "data.json", Config::JSON);
        $form = new SimpleForm(function (Player $player, int $data = null) use ($report, $dataa) {
            if ($data === null) return;
            switch ($data) {
                case 0:
                    if (Server::getInstance()->getPlayer($dataa->getNested("reports.$report.player")) !== null) {
                        $sender = Server::getInstance()->getPlayer($dataa->getNested("reports.$report.player"));
                        $player->teleport($sender->getPosition());
                        $player->sendMessage(ReportMain::getInstance()->getConfigValue("tp_good"));
                    } else $player->sendMessage(ReportMain::getInstance()->getConfigValue("player_offline"));
                    break;
                case 1:
                    if (ReportMain::getInstance()->getConfigValue("discord") === true) {
                        if (ReportMain::getInstance()->getConfigValue("webhook") !== "") {
                            if (ReportMain::getInstance()->getConfigValue("type") === "message") {
                                DiscordAPI::sendMessage(str_replace([strtolower('{player}'), strtolower('{report}')], [$player->getName(), $report], ReportMain::getInstance()->getConfigValue("messagea")));
                            }else DiscordAPI::sendEmbed(str_replace([strtolower('{player}'), strtolower('{report}')], [$player->getName(), $report], ReportMain::getInstance()->getConfigValue("content_embed_del")));
                        }
                    }

                    $player->sendMessage(ReportMain::getInstance()->getConfigValue("report_del"));
                    $dataa->removeNested("reports.$report");
                    $dataa->save();
                    break;


            }
        });
        $form->setTitle(ReportMain::getInstance()->getConfigValue("titlere"));
        $form->setContent(str_replace([
            strtolower("{title}"),
            strtolower("{author}"),
            strtolower("{player}"),
            strtolower("{desc}")
        ], [
            $report,
            $dataa->getNested("reports.$report.report"),
            $dataa->getNested("reports.$report.player"),
            $dataa->getNested("reports.$report.description")
        ], ReportMain::getInstance()->getConfigValue("contentr")));
        $form->addButton(ReportMain::getInstance()->getConfigValue("teleportation"));
        $form->addButton(ReportMain::getInstance()->getConfigValue("del"));
        $form->sendToPlayer($player);
        return $form;
    }
}