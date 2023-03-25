<?php

namespace THS\Forms;

use jojoe77777\FormAPI\CustomForm;
use jojoe77777\FormAPI\SimpleForm;
use pocketmine\command\ConsoleCommandSender;
use pocketmine\Player;
use pocketmine\Server;
use THS\API\LanguageAPI;
use THS\API\PlayersAPI;
use THS\Main;

class BoutiqueForms{
    public static function form(Player $player){
        $form = new SimpleForm(function (Player $player, int $data = null){
            if ($data === null) return;
            switch ($data){
                case 0:
                    self::grade($player);
                    break;
                case 1:
                    self::box($player);
                    break;
            }
        });
        if (LanguageAPI::getLanguage($player) === "fr") $form->setTitle("§a- §fBoutique §a-"); else $form->setTitle("§a- §fShop §a-");
        if (LanguageAPI::getLanguage($player) === "fr") $form->addButton("§a- §fGrade §a-"); else $form->addButton("§a- §fRank §a-");
        $form->addButton("§a- §fBox §a-");
        if (LanguageAPI::getLanguage($player) === "fr") $form->addButton("§l§cRetour"); else $form->addButton("§l§cBack");
        $form->sendToPlayer($player);
        return $form;
    }

    public static function grade(Player $player){
        $form = new SimpleForm(function (Player $player, int $data = null){
            if ($data === null) return;
            switch ($data) {
                case 0:
                    self::buyGrade($player, "VIP", 10);
                    break;
                case 1:
                    self::buyGrade($player, "Tatar", 20);
                    break;
                case 2:
                    self::buyGrade($player, "Legende", 30);
                    break;
                case 3:
                    self::buyGrade($player, "Champion", 40);
                    break;
                case 4:
                    self::buyGrade($player, "patrones", 60);
                    break;
                case 5:
                    self::buyGrade($player, "Supreme", 80);
                    break;
                case 6:
                    self::perso($player);
                    break;
                case 7:
                    self::youtube($player);
                    break;
                case 8:
                    self::form($player);
                    break;
            }
        });
        if (LanguageAPI::getLanguage($player) === "fr") $form->setTitle("§a- §fGrade §a-"); else $form->setTitle("§a- §fRank §a-");
        $form->addButton("§a- §fVIP §a-\n§a10 §fpoints boutique");
        $form->addButton("§a- §fTatar §a-\n§a20 §fpoints boutique");
        $form->addButton("§a- §fLégende §a-\n§a30 §fpoints boutique");
        $form->addButton("§a- §fChampion §a-\n§a40 §fpoints boutique");
        $form->addButton("§a- §fEl patrones §a-\n§a60 §fpoints boutique");
        $form->addButton("§a- §fSuprème §a-\n§a80 §fpoints boutique");
        $form->addButton("§a- §fPersonalisé §a-");
        $form->addButton("§a- §fYoutubeur §a-");
        if (LanguageAPI::getLanguage($player) === "fr") $form->addButton("§l§cRetour"); else $form->addButton("§l§cBack");
        $form->sendToPlayer($player);
        return $form;
    }

    public static function box(Player $player){
        $form = new SimpleForm(function (Player $player, int $data = null){
            if ($data === null) return;
            switch ($data){
                case 0:
                    self::buyBox($player, "master", 1);
                    break;
                case 1:
                    self::buyBox($player, "ths", 2);
                    break;
                case 2:
                    self::buyBox($player, "hope", 3);
                    break;
                case 3:
                    self::form($player);
                    break;
            }
        });
        $form->setTitle("§a- §fBox §a-");
        $form->addButton("§a- §fMaster §a-\n§a1 §fpoints boutique");
        $form->addButton("§a- §fThs §a-\n§a2 §fpoints boutique");
        $form->addButton("§a- §fHope §a-\n§a3 §fpoints boutique");
        if (LanguageAPI::getLanguage($player) === "fr") $form->addButton("§l§cRetour"); else $form->addButton("§l§cBack");
        $form->sendToPlayer($player);
        return $form;
    }





    public static function youtube(Player $player){
        $form = new SimpleForm(function (Player $player, int $data = null){
            if ($data === null) return;
            switch ($data){
                case 0:
                    self::form($player);
                    break;
            }
        });
        $form->setTitle("§a- §fYoutube §a-");
        $form->setContent("Pour avoir se grade il te faut 150 abonné avec 100 vues par vidéo , avec des montages, une miniature et de posté une vidéo sur le serveur. Les avantages:\nKit Tarta");
        if (LanguageAPI::getLanguage($player) === "fr") $form->addButton("§l§cRetour"); else $form->addButton("§l§cBack");
        $form->sendToPlayer($player);
    }

    public static function perso(Player $player){
        $form = new CustomForm(function (Player $player, array $data = null){
            if ($data === null) return;
            if ($data[3] !== null){
                if (strlen($data[3]) <= 10){
                    PlayersAPI::setInfo($player, "boutique", PlayersAPI::getInfo($player, "boutique") - 50);
                    Server::getInstance()->getCommandMap()->dispatch(new ConsoleCommandSender(), "addgroup $data[3]");
                    Server::getInstance()->getCommandMap()->dispatch(new ConsoleCommandSender(), "setgroup {$player->getName()} $data[3]");
                }else $player->sendMessage(Main::$prefix."Le grade ne doit pas faire plus de 10 caractères !");
            }else $player->sendMessage(Main::$prefix."Vous devez indiquer le nom d'un grade !");
        });
        $form->setTitle("§a- §fBoutique §a-");
        $form->addLabel("Choisissez le nom du grade que vous voulez:");
        $form->addLabel("Tous nom de grade discriminatoires insultant critiquant ect se verra supprimer du joueur avec regive de 50 Points Boutique si il recommence celui-ci se verra sont grade se supprimer et non remboursé");
        $form->addInput("Nom:");
        $form->sendToPlayer($player);
        return $form;
    }





    public static function buyGrade(Player $player, string $grade, $boutique){
        if (PlayersAPI::getInfo($player, "boutique") <= $boutique){
            LanguageAPI::sendMessage($player, "Vous n'avez pas assez de point boutique !", "You don't have enough store points!");
            return;
        }

        PlayersAPI::setInfo($player, "boutique", PlayersAPI::getInfo($player, "boutique") - $boutique);

        Server::getInstance()->getCommandMap()->dispatch(new ConsoleCommandSender(), "setgroup {$player->getName()} $grade");
    }

    public static function buyBox(Player $player, string $box, $boutique){
        if (PlayersAPI::getInfo($player, "boutique") <= $boutique){
            LanguageAPI::sendMessage($player, "Vous n'avez pas assez de point boutique !", "You don't have enough store points!");
            return;
        }

        PlayersAPI::setInfo($player, "boutique", PlayersAPI::getInfo($player, "boutique") - $boutique);

        Server::getInstance()->getCommandMap()->dispatch(new ConsoleCommandSender(), "givekey {$player->getName()} $box 1");
        $player->sendMessage(Main::$prefix."Vous venez d'acheter§a 1 §fclef §a$box §f!");
    }
}