<?php

namespace THS\API;

use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\item\Item;
use pocketmine\Player;
use pocketmine\Server;
use pocketmine\utils\Config;
use THS\Main;
use THS\Tasks\ScoreboardTask;
use THS\Tasks\ToggleSprintTask;

class JoinAPI{
    public static function money(Player $player){
        $money = new Config(Main::getInstance()->getDataFolder()."money.json",Config::JSON);
        $name = strtolower($player->getName());
        if (!$money->exists($name)){
            $money->set($name, 0);
            $money->save();
        }
    }

    public static function message(PlayerJoinEvent $event, Player $player){
        $event->setJoinMessage("");

        if ($player->hasPlayedBefore()){
            Server::getInstance()->broadcastTip("§a- §f{$player->getName()} §a-");
        }else LanguageAPI::sendAllMessage("§a{$player->getName()} §fest nouveau sur le serveur !", "§a{$player->getName()} §fis new on the server!");
    }

    public static function divers(Player $player){
        $slots = new Config(Main::getInstance()->getDataFolder()."slots.json",Config::JSON);
        $name = strtolower($player->getName());

        if (!PlayersAPI::existPlayer($player)){
            PlayersAPI::initPlayer($player);
        }

        // if (PlayersAPI::getInfo($player, "sprint") === true){
        //     Main::getInstance()->getScheduler()->scheduleRepeatingTask(new ToggleSprintTask($player), 20);
        // }

        if (!$slots->exists($player->getName())){
            $slots->setNested("{$player->getName()}.sword", 1);
            $slots->setNested("{$player->getName()}.compass", 3);
            $slots->setNested("{$player->getName()}.settings", 8);
            $slots->setNested("{$player->getName()}.ec", 5);
            $slots->save();
        }

        if (LanguageAPI::getLanguage($name) === "en"){
            $player->getInventory()->setItem($slots->getNested($player->getName().'.settings'), Item::get(Item::DRAGON_BREATH, 0, 1)->setCustomName("§r§a- §fSettings §a-"));
            $player->getInventory()->setItem($slots->getNested("{$player->getName()}.compass"), Item::get(Item::COMPASS, 0, 1)->setCustomName("§r§a- §fGames §a-"));
        }else{
            $player->getInventory()->setItem($slots->getNested("{$player->getName()}.compass"), Item::get(Item::COMPASS, 0, 1)->setCustomName("§r§a- §fJeux §a-"));
            $player->getInventory()->setItem($slots->getNested($player->getName().'.settings'), Item::get(Item::DRAGON_BREATH, 0, 1)->setCustomName("§r§a- §fParamètres §a-"));
        }

        $player->getInventory()->setItem($slots->getNested("{$player->getName()}.sword"), Item::get(Item::DIAMOND_SWORD, 0, 1)->setCustomName("§r§a- §fPvP §a-"));
        $player->getInventory()->setItem($slots->getNested("{$player->getName()}.ec"), Item::get(Item::ENDER_CHEST, 0, 1)->setCustomName("§r§a- §fEnderChest §a-"));


        $pos = Server::getInstance()->getDefaultLevel()->getSpawnLocation();
        $player->teleport($pos);

        LoadAPI::getScoreboard()->sendMainScoreboard($player);
        Main::getInstance()->getScheduler()->scheduleRepeatingTask(new ScoreboardTask($player), 20 * 16);

        $slotshika = new Config(Main::getInstance()->getDataFolder()."slotsgapple.json",Config::JSON);

        if (!$slotshika->exists($player->getName())){
            $info = [
                "sword" => 0,
                "apple" => 1,
                "heal" => 2
            ];
            $slotshika->set($player->getName(), $info);
            $slotshika->save();
        }
    }

    public static function xp(Player $player){
        $data = new Config(Main::getInstance()->getDataFolder()."xp.json",Config::JSON);
        if (!$data->exists($player->getName())){
            $info = [
                "gapple" => 0,
                "popo" => 0,
                "laser" => 0,
                "laser10" => 0,
                "heal" => 0,
                "arc" => 0,
                "hikabrain" => 0,
                "rush" => 0
            ];
            $data->set($player->getName(), $info);
            $data->save();
        }
    }
}