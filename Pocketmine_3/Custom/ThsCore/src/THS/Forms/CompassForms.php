<?php

namespace THS\Forms;

use jojoe77777\FormAPI\SimpleForm;
use pocketmine\Player;
use pocketmine\Server;
use pocketmine\utils\Config;
use THS\API\Play\ArcAPI;
use THS\API\Play\GappleAPI;
use THS\API\Play\HealStickAPI;
use THS\API\Play\KbAPI;
use THS\API\Play\PopoAPI;
use THS\API\Play\SnowAPI;
use HiroTeam\Hikabrain\forms\hikabrain\OpenHikabrainUI;
use HiroTeam\Hikabrain\forms\rush\OpenRushUI;
use THS\Main;

class CompassForms
{
    public static function formMenu(Player $player)
    {
        $language = new Config(Main::getInstance()->getDataFolder() . "language.json", Config::JSON);
        $form = new SimpleForm(function (Player $player, int $data = null) use ($language) {
            $result = $data;
            if ($result === null) {
                return true;
            }
            switch ($result) {
                case 0:
                    self::laser($player);
                    break;
                case 1:
                    new OpenHikabrainUI($player);
                    break;
                case 2:
                    new OpenRushUI($player);
                    break;
                case 3:
                    if ($language->get(strtolower($player->getName())) === "fr") {
                        $player->sendMessage(Main::$prefix . "Le mode de jeu TnTRun n'est pas encore arrivé !");
                    } else $player->sendMessage(Main::$prefix . "The TnTRun game mode has not yet arrived!");
                    break;
                case 4:
                    if ($language->get(strtolower($player->getName())) === "fr") {
                        $player->sendMessage(Main::$prefix . "Le mode de jeu DèsACoudre n'est pas encore arrivé !");
                    } else $player->sendMessage(Main::$prefix . "The DèsACoudre game mode has not yet arrived!");
                    break;
                // case 5:
                //     if ($language->get(strtolower($player->getName())) === "fr") {
                //         $player->sendMessage(Main::$prefix . "Le mode de jeu BowSpleef n'est pas encore arrivé !");
                //     } else $player->sendMessage(Main::$prefix . "The BowSpleef game mode has not yet arrived!");
                //     break;
                // case 6:
                //     if ($language->get(strtolower($player->getName())) === "fr") {
                //         $player->sendMessage(Main::$prefix . "Le mode de jeu One Shot n'est pas encore arrivé !");
                //     } else $player->sendMessage(Main::$prefix . "The One Shot game mode has not yet arrived!");
                //     break;
            }
        });
        $name = strtolower($player->getName());
        if ($language->get($name) === "fr") $form->setTitle("§a- §fJeux §a-"); else $form->setTitle("§a- §fGames §a-");
        if ($language->get($name) === "fr") $form->setContent("Choisissez le jeux que vous voulez:"); else $form->setContent("Choose the games you want:");
        $form->addButton("§b- §fLaser-Game §b-");
        $form->addButton("§a- §fHikabrain §a-");
        $form->addButton("§9- §fRush §9-\n§fBeta");
        $form->addButton("§d- §fTnTRun §d-");
        $form->addButton("§e- §fDès à Coudre §e-");
        $form->addButton("§2- §fHikaWars §2-");
        $form->addButton("§3- §fLBR §3-");
        // $form->addButton("§a- §fBowSpleef §a-");
        // $form->addButton("§c- §fOne Shot §c-");
        if ($language->get($name) === "fr") $form->addButton("§l§cRetour"); else $form->addButton("§l§cBack");
        $form->sendToPlayer($player);
        return $form;
    }

    public static function arcSnow(Player $player)
    {
        $language = new Config(Main::getInstance()->getDataFolder() . "language.json", Config::JSON);
        $form = new SimpleForm(function (Player $player, int $data = null) {
            $result = $data;
            if ($result === null) {
                return true;
            }
            switch ($result) {
                case 0:
                    ArcAPI::start($player);
                    break;
                case 1:
                    SnowAPI::start($player);
                    break;
                case 2:
                    self::formMenu($player);
                    break;
            }
        });
        $name = strtolower($player->getName());
        $form->setTitle("§a- §fArcher§a/§fSnow §a-");
        if ($language->get($name) === "fr") $form->setContent("Choisissez le kit que vous voulez:"); else $form->setContent("Choose the kit you want:");
        $form->addButton("§a- §fArcher §a-");
        $form->addButton("§a- §fSnow §a-");
        if ($language->get($name) === "fr") $form->addButton("§l§cRetour"); else $form->addButton("§l§cBack");
        $form->sendToPlayer($player);
        return $form;
    }

    public static function laser($player)
    {
        $language = new Config(Main::getInstance()->getDataFolder() . "language.json", Config::JSON);
        $form = new SimpleForm(function (Player $player, int $data = null) {
            $result = $data;
            if ($result === null) {
                return true;
            }
            switch ($result) {
                case 0:
                    Server::getInstance()->getCommandMap()->dispatch($player, "laserjoin");
                    $player->setNameTag(" ");
                    $player->setNameTagAlwaysVisible(false);
                    break;
                case 1:
                    Server::getInstance()->getCommandMap()->dispatch($player, "laserjoin10vs10");
                    $player->setNameTag(" ");
                    $player->setNameTagAlwaysVisible(false);
                    break;
                case 2:
                    self::formMenu($player);
                    break;
            }
        });
        if ($language->get(strtolower($player->getName())) === "en") {
            $form->setTitle("§0- §fLaser-Game §0-");
            $form->setContent("Choose the laser-game you want:");
            $form->addButton("§0- §fOnly §0-");
            $form->addButton("§0- §f10 vs 10 §0-");
            $form->addButton("§l§cBack");
        } else {
            $form->setTitle("§0- §fLaser-Game §0-");
            $form->setContent("Choisissez le laser-game que vous voulez:");
            $form->addButton("§0- §fSeul §0-");
            $form->addButton("§0- §f10 vs 10 §0-");
            $form->addButton("§l§cRetour");
        }
        $form->sendToPlayer($player);
        return $form;
    }


    // Kits
    public static function gappleKit(Player $player)
    {
        $language = new Config(Main::getInstance()->getDataFolder() . "language.json", Config::JSON);
        $name = strtolower($player->getName());
        $form = new SimpleForm(function (Player $player, int $data = null) use ($language, $name) {
            $result = $data;
            if ($result === null) return;
            switch ($result) {
                case 0:
                    GappleAPI::player($player);
                    break;
                case 1:
                    if ($player->hasPermission("vip.kit")) {
                        GappleAPI::vip($player);
                    } else {
                        if ($language->get($name) === "fr") $player->sendMessage(Main::$prefix . "Vous n'avez pas la permission de prendre ce kit !"); else $player->sendMessage(Main::$prefix . "You are not allowed to take this kit!");
                    }
                    break;
                case 2:
                    if ($player->hasPermission("tatar.kit")) {
                        GappleAPI::tatar($player);
                    } else {
                        if ($language->get($name) === "fr") $player->sendMessage(Main::$prefix . "Vous n'avez pas la permission de prendre ce kit !"); else $player->sendMessage(Main::$prefix . "You are not allowed to take this kit!");
                    }
                    break;
                case 3:
                    if ($player->hasPermission("legende.kit")) {
                        GappleAPI::legende($player);
                    } else {
                        if ($language->get($name) === "fr") $player->sendMessage(Main::$prefix . "Vous n'avez pas la permission de prendre ce kit !"); else $player->sendMessage(Main::$prefix . "You are not allowed to take this kit!");
                    }
                    break;
                case 4:
                    if ($player->hasPermission("champion.kit")) {
                        GappleAPI::champion($player);
                    } else {
                        if ($language->get($name) === "fr") $player->sendMessage(Main::$prefix . "Vous n'avez pas la permission de prendre ce kit !"); else $player->sendMessage(Main::$prefix . "You are not allowed to take this kit!");
                    }
                    break;
                case 5:
                    if ($player->hasPermission("patrones.kit")) {
                        GappleAPI::patrones($player);
                    } else {
                        if ($language->get($name) === "fr") $player->sendMessage(Main::$prefix . "Vous n'avez pas la permission de prendre ce kit !"); else $player->sendMessage(Main::$prefix . "You are not allowed to take this kit!");
                    }
                    break;
                case 6:
                    if ($player->hasPermission("supreme.kit")) {
                        GappleAPI::supreme($player);
                    } else {
                        if ($language->get($name) === "fr") $player->sendMessage(Main::$prefix . "Vous n'avez pas la permission de prendre ce kit !"); else $player->sendMessage(Main::$prefix . "You are not allowed to take this kit!");
                    }
                    break;
            }
        });
        $form->setTitle("§a- §fKits §a-");
        if ($language->get($name) === "en") $form->setContent("Choose the kit you want:"); else $form->setContent("Choisissez le kit que vous voulez:");
        $form->addButton("§0- §fPlayer §0-");
        $form->addButton("§6- §fVIP §6-");
        $form->addButton("§a- §fTatar §a-");
        $form->addButton("§1- §fLegende §1-");
        $form->addButton("§d- §fChampion §d-");
        $form->addButton("§c- §fEl Patrones §c-");
        $form->addButton("§e- §fSuprème §e-");
        if ($language->get($name) === "en") $form->addButton("§l§cBack"); else $form->addButton("§l§cRetour");
        $form->sendToPlayer($player);
        return $form;
    }

    public static function popoKit(Player $player)
    {
        $language = new Config(Main::getInstance()->getDataFolder() . "language.json", Config::JSON);
        $name = strtolower($player->getName());
        $form = new SimpleForm(function (Player $player, int $data = null) use ($language, $name) {
            $result = $data;
            if ($result === null) return false;
            switch ($result) {
                case 0:
                    PopoAPI::player($player);
                    break;
                case 1:
                    if ($player->hasPermission("vip.kit")) {
                        PopoAPI::vip($player);
                    } else {
                        if ($language->get($name) === "fr") $player->sendMessage(Main::$prefix . "Vous n'avez pas la permission de prendre ce kit !"); else $player->sendMessage(Main::$prefix . "You are not allowed to take this kit!");
                    }
                    break;
                case 2:
                    if ($player->hasPermission("tatar.kit")) {
                        PopoAPI::tatar($player);
                    } else {
                        if ($language->get($name) === "fr") $player->sendMessage(Main::$prefix . "Vous n'avez pas la permission de prendre ce kit !"); else $player->sendMessage(Main::$prefix . "You are not allowed to take this kit!");
                    }
                    break;
                case 3:
                    if ($player->hasPermission("legende.kit")) {
                        PopoAPI::legende($player);
                    } else {
                        if ($language->get($name) === "fr") $player->sendMessage(Main::$prefix . "Vous n'avez pas la permission de prendre ce kit !"); else $player->sendMessage(Main::$prefix . "You are not allowed to take this kit!");
                    }
                    break;
                case 4:
                    if ($player->hasPermission("champion.kit")) {
                        PopoAPI::champion($player);
                    } else {
                        if ($language->get($name) === "fr") $player->sendMessage(Main::$prefix . "Vous n'avez pas la permission de prendre ce kit !"); else $player->sendMessage(Main::$prefix . "You are not allowed to take this kit!");
                    }
                    break;
                case 5:
                    if ($player->hasPermission("patrones.kit")) {
                        PopoAPI::patrones($player);
                    } else {
                        if ($language->get($name) === "fr") $player->sendMessage(Main::$prefix . "Vous n'avez pas la permission de prendre ce kit !"); else $player->sendMessage(Main::$prefix . "You are not allowed to take this kit!");
                    }
                    break;
                case 6:
                    if ($player->hasPermission("supreme.kit")) {
                        PopoAPI::supreme($player);
                    } else {
                        if ($language->get($name) === "fr") $player->sendMessage(Main::$prefix . "Vous n'avez pas la permission de prendre ce kit !"); else $player->sendMessage(Main::$prefix . "You are not allowed to take this kit!");
                    }
                    break;
            }
        });
        $name = strtolower($player->getName());
        $form->setTitle("§a- §fKits §a-");
        if ($language->get($name) === "en") $form->setContent("Choose the kit you want:"); else $form->setContent("Choisissez le kit que vous voulez:");
        $form->addButton("§0- §fPlayer §0-");
        $form->addButton("§6- §fVIP §6-");
        $form->addButton("§a- §fTatar §a-");
        $form->addButton("§1- §fLegende §1-");
        $form->addButton("§d- §fChampion §d-");
        $form->addButton("§c- §fEl Patrones §c-");
        $form->addButton("§e- §fSuprème §e-");
        if ($language->get($name) === "en") $form->addButton("§l§cBack"); else $form->addButton("§l§cRetour");
        $form->sendToPlayer($player);
        return $form;
    }

    public static function diamondForm(Player $player)
    {
        $language = new Config(Main::getInstance()->getDataFolder() . "language.json", Config::JSON);
        $form = new SimpleForm(function (Player $player, int $data = null) {
            if ($data === null) return false;
            switch ($data) {
                case 0:
                    GappleAPI::startGapple($player);
                    break;
                case 1:
                    PopoAPI::start($player);
                    break;
                // case 2:
                //     self::arcSnow($player);
                //     break;
                case 2:
                    HealStickAPI::startHeal($player);
                    break;
                case 3:
                    KbAPI::startKB($player);
                    break;
                case 4:
                    $player->sendMessage(Main::$prefix."Les bots ne sont pas encore arrivé !");
                    break;
            }
        });
        $gapple = 0;
        foreach (Server::getInstance()->getLevelByName("Gapple")->getPlayers() as $players) {
            $gapple++;
        }

        $popo = 0;
        foreach (Server::getInstance()->getLevelByName("Popo")->getPlayers() as $players) {
            $popo++;
        }

        $arc = 0;
        foreach (Server::getInstance()->getLevelByName("Arc")->getPlayers() as $players) {
            $arc++;
        }

        $heal = 0;
        foreach (Server::getInstance()->getLevelByName("Heal")->getPlayers() as $players) {
            $heal++;
        }

        $kb = 0;
        foreach (Server::getInstance()->getLevelByName("KB")->getPlayers() as $players) {
            $kb++;
        }

        $name = strtolower($player->getName());
        $form->setTitle("§a- §fPVP§a -");
        if ($language->get($name) === "fr") $form->setContent("Choisissez ce que vous voulez:"); else $form->setContent("Choose what you want:");
        $form->addButton("§e- §fGapple §e-\n$gapple joueur(s)");
        $form->addButton("§c- §fNodebuff §c-\n$popo joueur(s)");
        // $form->addButton("§a- §fArcher§a/§fSnow §a-\n$arc joueur(s)");
        $form->addButton("§b- §fHealStick §b-\n$heal joueur(s)");
        $form->addButton("§d- §fKB §d-\n$kb joueur(s)");
        $form->addButton("§a- §fBot §a-");
        if ($language->get($name) === "fr") $form->addButton("§l§cRetour"); else $form->addButton("§l§cBack");
        $form->sendToPlayer($player);
        return $form;
    }
}