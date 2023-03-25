<?php

namespace THS\Forms;

use jojoe77777\FormAPI\CustomForm;
use jojoe77777\FormAPI\SimpleForm;
use pocketmine\Player;
use pocketmine\Server;
use pocketmine\utils\Config;
use THS\API\LanguageAPI;
use THS\Main;

class BanForms{
    public static function ban(Player $player){
        $form = new SimpleForm(function (Player $player, int $data = null){
            if ($data === null){
                return true;
            }
            switch ($data){
                case 0:
                    self::temp($player);
                    break;
                case 1:
                    self::perm($player);
                    break;
            }
        });
        $form->setTitle("§a- §fBan §a-");
        if (LanguageAPI::getLanguage($player) === "fr") $form->setContent("Choisissez la méthode de banissement:"); else $form->setContent("Choose the ban method:");
        if (LanguageAPI::getLanguage($player) === "fr") $form->addButton("§0- §fTemporaire §0-"); else $form->addButton("§0- §fTemporary §0-");
        $form->addButton("§0- §fPerm §0-");
        if (LanguageAPI::getLanguage($player) === "fr") $form->addButton("§l§cRetour"); else $form->addButton("§c§lBack");
        $form->sendToPlayer($player);
        return $form;
    }

    public static function temp(Player $player){
        $tban = new Config(Main::getInstance()->getDataFolder()."tban.json", Config::JSON);
        $form = new CustomForm(function (Player $player, array $data = null) use ($tban){
            if ($data === null){
                return self::ban($player);
            }
            if ($data[1] === null){
                LanguageAPI::sendMessage($player, "Vous devez indiqué un joueur !", "You must indicate a player !");
                return true;
            }

            if ($data[2] === null) {
                LanguageAPI::sendMessage($player, "Vous devez indiqué une raison !", "You must indicate a reason !");
                return true;
            }

            if (Server::getInstance()->getPlayer($data[1]) !== null){
                $senderr = Server::getInstance()->getPlayer($data[1]);
                $sender = $senderr->getName();
            }else $sender = $data[1];

            if ($data)
            $temps = ($data[3] * 60) + ($data[4] * 60 * 60) + ($data[5] * 60 * 60 * 24);

            $ban = [
                "staff" => $player->getName(),
                "raison" => $data[2],
                "temps" => time() + $temps
            ];
            $tban->set($sender, $ban);
            $tban->save();

            LanguageAPI::sendMessage($player, "Vous venez de bannir§a $sender §fpour§a $data[1] §f!", "You just banned§a $sender §ffor§a $data[1] §f!");
            LanguageAPI::sendAllMessage("Le joueur§a $sender §fvient d'être banni par§a {$player->getName()} §fpour§a $data[1] §f!", "The player§a $sender §fhas just banned by§a {$player->getName()} §f!");
        });
        $form->setTitle("§a- §fBan §a-");
        if (LanguageAPI::getLanguage($player) === "fr") $form->addInput("Joueur"); else $form->addInput("Player");
        if (LanguageAPI::getLanguage($player) === "fr") $form->addInput("Raison"); else $form->addInput("Reason");
        $form->addSlider("Minute(s)", 1, 60, 1, 30);
        if (LanguageAPI::getLanguage($player) === "fr") $form->addSlider("Heure(s)", 0, 24); else $form->addSlider("Hour(s)", 0, 24);
        if (LanguageAPI::getLanguage($player) === "fr") $form->addSlider("Jour(s)", 0, 30); else $form->addSlider("Day(s)", 0, 30);
        $form->sendToPlayer($player);
        return $form;
    }

    public static function perm(Player $player){
        $banperm = new Config(Main::getInstance()->getDataFolder()."banperm.json", Config::JSON);
        $form = new CustomForm(function (Player $player, array $data = null) use ($banperm){
            if ($data === null){
                return self::ban($player);
            }

            if ($data[1] === null){
                LanguageAPI::sendMessage($player, "Vous devez indiqué un joueur !", "You must indicate a player !");
                return true;
            }

            if ($data[2] === null){
                LanguageAPI::sendMessage($player, "Vous devez indiqué une raison !", "You must indicate a reason !");
                return true;
            }

            if (Server::getInstance()->getPlayer($data[1]) !== null){
                $senderr = Server::getInstance()->getPlayer($data[1]);
                $sender = $senderr->getName();
            }else $sender = $data[1];

            if ($banperm->exists($sender)){
                LanguageAPI::sendMessage($player, "Le joueur§a $sender §fest déja banni !", "The player $sender is already banned");
                return true;
            }

            $banperm->set($sender, $data[2]);
            $banperm->save();

            if ($data[1] instanceof Player){
                $senderr->close($data[2], $data[2]);
            }
            LanguageAPI::sendMessage($player, "Le joueur§a $sender §fa bien été banni !", "The player§a $sender §fhas been banned!");
            return true;
        });
        $form->setTitle("§a- §fBan §a-");
        if (LanguageAPI::getLanguage($player) === "fr") $form->addInput("Joueur"); else $form->addInput("Player");
        if (LanguageAPI::getLanguage($player) === "fr") $form->addInput("Raison"); else $form->addInput("Reason");
        $form->sendToPlayer($player);
        return $form;
    }
}