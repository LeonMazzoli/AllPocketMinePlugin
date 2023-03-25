<?php

namespace Digueloulou12\Forms;

use Digueloulou12\Main;
use jojoe77777\FormAPI\CustomForm;
use jojoe77777\FormAPI\SimpleForm;
use pocketmine\Player;
use pocketmine\Server;
use pocketmine\utils\Config;

class ReportForms
{
    public static function reportForm(Player $player)
    {
        $members = [];
        foreach (Server::getInstance()->getOnlinePlayers() as $member) {
            $members[] = $member->getName();
        }
        $dataa = new Config(Main::getInstance()->getDataFolder() . "reports.json", Config::JSON);
        $form = new CustomForm(function (Player $player, array $data = null) use ($dataa, $members) {
            if ($data === null) return;
            if (!isset($data[1])) {
                $player->sendMessage(Main::getConfigAPI()->getConfigValue("report_no_title"));
                return;
            }

            if (!isset($data[3])) {
                $player->sendMessage(Main::getConfigAPI()->getConfigValue("report_no_desc"));
                return;
            }

            if ($dataa->getNested("reports.$data[1]") !== null) {
                $player->sendMessage(Main::getConfigAPI()->getConfigValue("report_exist"));
                return;
            }

            $sender = Server::getInstance()->getPlayer($members[$data[2]]);

            $info = ["title" => $data[1], "player" => $members[$data[2]], "report" => $player->getName(), "description" => $data[3]];
            $dataa->setNested("reports.$data[1]", $info);
            $dataa->save();

            // Alert
            if (Main::$config->get("report_alert_staff") === true) {
                foreach (Server::getInstance()->getOnlinePlayers() as $senderr) {
                    if ($senderr->hasPermission(Main::getConfigAPI()->getConfigValue("report_perm_alert"))) {
                        $senderr->sendMessage(Main::getConfigAPI()->getConfigValue("report_alert_staff_msg", ["{player}"], [$player->getName()]));
                    }
                }
            }

            // Alert Player Report
            if (Main::$config->get("report_alert_player") === true) {
                if ($sender instanceof Player) {
                    $sender->sendMessage(Main::getConfigAPI()->getConfigValue("report_alert_player_msg", ["{player}"], [$player->getName()]));
                }
            }

            // Discord
            if (Main::$config->get("report_discord") === true) {
                if (Main::getConfigAPI()->getConfigValue("report_discord_type") === "message") {
                    Main::getDiscordAPI()->sendMessage(Main::getConfigAPI()->getConfigValue("report_discord_msg", ["{player}", "{sender}", "{desc}"], [$player->getName(), $members[$data[2]], $data[3]]));
                } else Main::getDiscordAPI()->sendEmbed(Main::getConfigAPI()->getConfigValue("report_embed", ["{player}", "{sender}", "{desc}"], [$player->getName(), $members[$data[2]], $data[3]]), Main::getConfigAPI()->getConfigValue("report_embed_title"));
            }
        });
        $form->setTitle(Main::getConfigAPI()->getConfigValue("report_title"));
        $form->addLabel(Main::getConfigAPI()->getConfigValue("report_label"));
        $form->addInput(Main::getConfigAPI()->getConfigValue("report_input_title"));
        $form->addDropdown(Main::getConfigAPI()->getConfigValue("report_dropdown"), $members);
        $form->addInput(Main::getConfigAPI()->getConfigValue("report_input_description"));
        $form->sendToPlayer($player);
        return $form;
    }

    public static function reportAdmin(Player $player)
    {
        $dataa = new Config(Main::getInstance()->getDataFolder() . "reports.json", Config::JSON);
        $form = new SimpleForm(function (Player $player, $data = null) use ($dataa) {
            if ($data === null) return;
            self::report($player, $data);
        });
        $form->setTitle(Main::getConfigAPI()->getConfigValue("report_title"));
        $form->setContent(Main::getConfigAPI()->getConfigValue("reportadmin_content1"));
        foreach ($dataa->getAll()["reports"] as $reports => $keys) {
            $form->addButton($keys["title"], -1, "", $keys["title"]);
        }
        $form->sendToPlayer($player);
        return $form;
    }


    public static function report(Player $player, $report)
    {
        $dataa = new Config(Main::getInstance()->getDataFolder() . "reports.json", Config::JSON);
        $form = new SimpleForm(function (Player $player, int $data = null) use ($report, $dataa) {
            if ($data === null) return;
            switch ($data) {
                case 0:
                    if (Server::getInstance()->getPlayer($dataa->getNested("reports.$report.player")) !== null) {
                        $sender = Server::getInstance()->getPlayer($dataa->getNested("reports.$report.player"));
                        $player->teleport($sender->getPosition());
                        $player->sendMessage(Main::getConfigAPI()->getConfigValue("reportadmin_tp_good_msg"));
                    } else $player->sendMessage(Main::getConfigAPI()->getConfigValue("reportadmin_tp_offline_msg"));
                    break;
                case 1:
                    if (Main::$config->get("report_discord") === true) {
                        if (Main::getConfigAPI()->getConfigValue("report_discord_type") === "message") {
                            Main::getDiscordAPI()->sendMessage(Main::getConfigAPI()->getConfigValue("report_discord_del_msg", ["{player}", "{report}"], [$player->getName(), $report]));
                        } else Main::getDiscordAPI()->sendEmbed(Main::getConfigAPI()->getConfigValue("report_embed_del", ["{player}", "{report}"], [$player->getName(), $report]), Main::getConfigAPI()->getConfigValue("report_embed_title"));
                    }

                    $player->sendMessage(Main::getConfigAPI()->getConfigValue("reportadmin_del_msg"));
                    $dataa->removeNested("reports.$report");
                    $dataa->save();
                    break;
            }
        });
        $form->setTitle(Main::getConfigAPI()->getConfigValue("report_title"));
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
        ], Main::getConfigAPI()->getConfigValue("reportadmin_content")));
        $form->addButton(Main::getConfigAPI()->getConfigValue("reportadmin_button_teleportation"));
        $form->addButton(Main::getConfigAPI()->getConfigValue("reportadmin_button_del"));
        $form->sendToPlayer($player);
        return $form;
    }
}