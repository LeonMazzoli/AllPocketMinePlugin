<?php

namespace THS\Forms;

use jojoe77777\FormAPI\CustomForm;
use jojoe77777\FormAPI\SimpleForm;
use pocketmine\Player;
use pocketmine\Server;
use pocketmine\utils\Config;
use THS\API\Game;
use THS\API\LanguageAPI;
use THS\API\LoadAPI;
use THS\API\PlayersAPI;
use THS\Main;

class SettingsForms{
    public static function settings(Player $player){
        $language = new Config(Main::getInstance()->getDataFolder()."language.json",Config::JSON);
        $name = strtolower($player->getName());
        $form = new SimpleForm(function (Player $player, int $data = null){
            if ($data === null) return false;
            switch ($data){
                //case 0:
                //                    $rank = Server::getInstance()->getPluginManager()->getPlugin("PurePerms")->getUserDataMgr()->getGroup($player);
                //
                //                    $player->sendMessage("§f----- §aTHS §f-----");
                //                    if (LanguageAPI::getLanguage($player) === "fr") $player->sendMessage("§aJoueur:"); else $player->sendMessage("§aPlayer:");
                //                    $player->sendMessage(" §fPseudo: §a{$player->getName()}");
                //                    $player->sendMessage(" §fGrade: §a$rank");
                //                    $player->sendMessage(" §fMoney: §a". MoneyAPI::myMoney($player));
                //                    $player->sendMessage("  ");
                //                    if (LanguageAPI::getLanguage($player) === "fr") $player->sendMessage("§aServeur:"); else $player->sendMessage("§aServer:");
                //                    if (LanguageAPI::getLanguage($player) === "fr") $player->sendMessage(" §fEn ligne: §a".count(Server::getInstance()->getOnlinePlayers())."§f/§a".Server::getInstance()->getMaxPlayers()); else $player->sendMessage(" §fIn line: §a".count(Server::getInstance()->getOnlinePlayers())."§f/§a".Server::getInstance()->getMaxPlayers());
                //                    $player->sendMessage(" §fVoteParty: §asoon...");
                //                    $player->sendMessage("§f--- §aThs-mc.fr §f---");
                //                    break;
                case 0:
                    self::chooseSlot($player);
                    break;
                case 1:
                    self::language($player);
                    break;
                case 2:
                    // if (PlayersAPI::getInfo($player, "sprint") === true){
                    //                        Main::getInstance()->getScheduler()->cancelTask(ToggleSprintTask::$taskId);
                    //                        PlayersAPI::setInfo($player, "sprint", false);
                    //                    }else {
                    //                        Main::getInstance()->getScheduler()->scheduleRepeatingTask(new ToggleSprintTask($player), 20);
                    //                        PlayersAPI::setInfo($player, "sprint", true);
                    //                    }
                    Server::getInstance()->getCommandMap()->dispatch($player, "togglesprint");
                    break;
                case 3:
                    if (PlayersAPI::getInfo($player, "scoreboard") === false){
                        PlayersAPI::setInfo($player, "scoreboard", true);
                        LoadAPI::getScoreboard()->sendMainScoreboard($player);
                    }else{
                        PlayersAPI::setInfo($player, "scoreboard", false);
                        LoadAPI::getScoreboard()->removeScoreboard($player);
                    }
                    break;
            }
        });
        if ($language->get($name) === "fr") $form->setTitle("§a- §fParamètres §a-"); else $form->setTitle("§a- §fSettings §a-");
        if ($language->get($name) === "fr") $form->setContent("Voici les paramètres du serveur:"); else $form->setContent("Here are the server settings:");
        // $form->addButton("§0- §fInfo Ths §0-");
        $form->addButton("§c- §fSlots §c-");
        if ($language->get($name) === "fr") $form->addButton("§b- §fLangue §b-"); else $form->addButton("§b- §fLanguage §b-");
        $form->addButton("§d- §fToggleSprint §d-");
        $form->addButton("§a- §fScoreboard §a-");
        if ($language->get($name) === "fr") $form->addButton("§l§cRetour"); else $form->addButton("§l§cBack");
        $form->sendToPlayer($player);
        return $form;
    }

    public static function language(Player $player){
        $language = new Config(Main::getInstance()->getDataFolder()."language.json",Config::JSON);
        $slots = new Config(Main::getInstance()->getDataFolder()."slots.json",Config::JSON);
        $name = strtolower($player->getName());
        $form = new SimpleForm(function (Player $player, int $data = null) use ($name, $language, $slots){
            if ($data === null) return self::settings($player);
            switch ($data){
                case 0:
                    Game::removeItem($player);
                    $language->set($name, "fr");
                    Game::addItem($player);
                    $player->sendMessage(Main::$prefix."Vous venez de changer votre langue !");
                    break;
                case 1:
                    Game::removeItem($player);
                    $language->set($name, "en");
                    Game::addItem($player);
                    $player->sendMessage(Main::$prefix."You just changed your language!");
                    break;
                case 2:
                    self::settings($player);
                    break;
            }
            $language->save();
        });
        if ($language->get($name) === "fr") $form->setTitle("§a- §fParamètres §a-"); else $form->setTitle("§a- §fSettings §a-");
        if ($language->get($name) === "fr") $form->setContent("Choisissez la langue que vous voulez:"); else $form->setContent("Choose the language you want:");
        if ($language->get($name) === "fr") $form->addButton("§b- §fFrançais §b-"); else $form->addButton("§b- §fFrench §b-");
        if ($language->get($name) === "fr") $form->addButton("§c- §fAnglais §c-"); else $form->addButton("§c- §cEnglish §c-");
        if ($language->get($name) === "fr") $form->addButton("§l§cRetour"); else $form->addButton("§l§cBack");
        $form->sendToPlayer($player);
        return $form;
    }

    public static function slotsForm(Player $player){
        $slots = new Config(Main::getInstance()->getDataFolder()."slots.json",Config::JSON);
        $language = new Config(Main::getInstance()->getDataFolder()."language.json",Config::JSON);
        $form = new CustomForm(function (Player $player, array $data = null) use ($slots, $language){
            if ($data === null) return self::settings($player);
            $namee = $player->getName();
            switch ($data[1]){
                case 0:
                    if (($slots->getNested("$namee.settings") !== $data[2]) and ($slots->getNested("$namee.compass") !== $data[2]) and ($slots->getNested("$namee.ec") !== $data[2])){
                        $slots->setNested("{$player->getName()}.sword", $data[2]);
                    }else LanguageAPI::sendMessage($player, "Il y a déja un item sur le slot§a $data[2] §f!", "There is already an item on the slot§a $data[2] §f!");
                    break;
                case 1:
                    if (($slots->getNested("$namee.sword") !== $data[2]) and ($slots->getNested("$namee.settings") !== $data[2]) and ($slots->getNested("$namee.ec") !== $data[2])) {
                        $slots->setNested("{$player->getName()}.compass", $data[2]);
                    }else LanguageAPI::sendMessage($player, "Il y a déja un item sur le slot§a $data[2] §f!", "There is already an item on the slot§a $data[2] §f!");
                    break;
                case 2:
                    if (($slots->getNested("$namee.sword") !== $data[2]) and ($slots->getNested("$namee.compass") !== $data[2])  and ($slots->getNested("$namee.ec") !== $data[2])) {
                        $slots->setNested("{$player->getName()}.settings", $data[2]);
                    }else LanguageAPI::sendMessage($player, "Il y a déja un item sur le slot§a $data[2] §f!", "There is already an item on the slot§a $data[2] §f!");
                    break;
                case 3:
                    if (($slots->getNested("$namee.sword") !== $data[2]) and ($slots->getNested("$namee.compass") !== $data[2]) and ($slots->getNested("$namee.settings") !== $data[2])) {
                        $slots->setNested("{$player->getName()}.ec", $data[2]);
                    }else LanguageAPI::sendMessage($player, "Il y a déja un item sur le slot§a $data[2] §f!", "There is already an item on the slot§a $data[2] §f!");
                    break;
            }
            $slots->save();

            Game::removeItem($player);
            Game::addItem($player);

        });
        $name = strtolower($player->getName());
        $form->setTitle("§a- §fSlots §a-");
        if ($language->get($name) === "fr") $form->addLabel("Choisissez l'item et le slot que vous voulez:"); else $form->addLabel("Choose the item and the slot you want:");
        if ($language->get($name) === "fr") $form->addDropdown("Item", ["Epée", "Boussole", "Paramètre", "EnderChest"]); else $form->addDropdown("Item", ["Sword", "Compass", "Settings", "EnderChest"]);
        $form->addSlider("Slot", 0, 8);
        $form->sendToPlayer($player);
        return $form;
    }

    public static function chooseSlot(Player $player){
        $language = new Config(Main::getInstance()->getDataFolder()."language.json",Config::JSON);
        $form = new SimpleForm(function (Player $player, int $data = null){
            if ($data === null) return self::settings($player);
            switch ($data){
                case 0:
                    self::gappleslot($player);
                    break;
                case 1:
                    self::slotsForm($player);
                    break;
                case 2:
                    self::settings($player);
                    break;
            }
        });
        $name = strtolower($player->getName());
        $form->setTitle("§a- §fSlots §a-");
        if ($language->get($name) === "fr") $form->setContent("Choisissez la catégorie dans la quelle vous voulez modifiez votre inventaire:"); else $form->setContent("Choose the category in which you want to modify your inventory:");
        $form->addButton("§0- §fGapple §0-");
        $form->addButton("§c- §fHub §c-");
        if ($language->get($name) === "fr") $form->addButton("§l§cRetour"); else $form->addButton("§l§cBack");
        $form->sendToPlayer($player);
        return $form;
    }

    public static function gappleslot(Player $player){
        $slotshika = new Config(Main::getInstance()->getDataFolder()."slotsgapple.json",Config::JSON);
        $form = new CustomForm(function (Player $player, array $data = null) use ($slotshika){
            if ($data === null) return self::chooseSlot($player);

            if (($data[1] === $data[2]) or ($data[1] === $data[3])) return $player->sendMessage(Main::$prefix."Les slots ne doivent pas être équivalent !");
            if (($data[2] === $data[1]) or ($data[2] === $data[3])) return $player->sendMessage(Main::$prefix."Les slots ne doivent pas être équivalent !");
            if (($data[3] === $data[2]) or ($data[3] === $data[1])) return $player->sendMessage(Main::$prefix."Les slots ne doivent pas être équivalent !");

            $info = [
                "sword" => $data[1],
                "apple" => $data[2],
                "heal" => $data[3]
            ];
            $slotshika->set($player->getName(), $info);
            $slotshika->save();
            return true;
        });
        $form->setTitle("§a- Slots §a-");
        $form->addSlider("Epee", 0, 8);
        $form->addSlider("Pomme en or", 0, 8);
        $form->addSlider("Heal", 0, 8);
        $form->sendToPlayer($player);
        return $form;
    }
}