<?php

namespace THS\Commands;

use pocketmine\command\CommandSender;
use pocketmine\command\ConsoleCommandSender;
use pocketmine\command\PluginCommand;
use pocketmine\entity\Effect;
use pocketmine\entity\EffectInstance;
use pocketmine\item\Item;
use pocketmine\Player;
use pocketmine\Server;
use pocketmine\utils\Config;
use THS\API\MoneyAPI;
use THS\Main;

class Grade extends PluginCommand{
    public function __construct(Main $main)
    {
        parent::__construct("grade", $main);
    }

    public function execute(CommandSender $player, string $commandLabel, array $args)
    {
        if (!($player instanceof Player)){
            $player->sendMessage(Main::$ig);
            return;
        }

        $config = new Config(Main::getInstance()->getDataFolder() . 'db.yml', CONFIG::YAML);
        $time = $config->get($player->getName());
        if (empty($time)) {
            $time = 0;
        }

        $timeNow = time();
        if ($player->hasPermission("supreme.grade")){
            if ($timeNow - $time >= (48 * 60 * 60)) {
                switch (mt_rand(1, 3)){
                    case 1:
                        MoneyAPI::addMoney($player, 16);
                        break;
                    case 2:
                        $player->getInventory()->addItem(Item::get(Item::GOLDEN_APPLE, 0, 16));
                        break;
                    case 3:
                        Server::getInstance()->getCommandMap()->dispatch(new ConsoleCommandSender(), "givekey {$player->getName()} master 2");
                        break;
                }
                $config->set($player->getName(), $timeNow);
            } else {
                $HourMinuteSecond = explode(":", gmdate("H:i:s", (48 * 60 * 60) - ($timeNow - $time)));
                $player->sendMessage("§f» §7Vous devez encore attendre§c $HourMinuteSecond[0] §7heure(s),§c $HourMinuteSecond[1] §7minute(s),§c $HourMinuteSecond[2] §7seconde(s) avant de pouvoir reprendre un kit !");
            }
        }elseif ($player->hasPermission("patrones.grade")){
            if ($timeNow - $time >= (42 * 60 * 60)) {
                switch (mt_rand(1, 3)){
                    case 1:
                        MoneyAPI::addMoney($player, 12);
                        break;
                    case 2:
                        $player->getInventory()->addItem(Item::get(Item::GOLDEN_APPLE, 0, 12));
                        break;
                    case 3:
                        Server::getInstance()->getCommandMap()->dispatch(new ConsoleCommandSender(), "givekey {$player->getName()} master 1");
                        break;
                }
                $config->set($player->getName(), $timeNow);
            } else {
                $HourMinuteSecond = explode(":", gmdate("H:i:s", (42 * 60 * 60) - ($timeNow - $time)));
                $player->sendMessage("§f» §7Vous devez encore attendre§c $HourMinuteSecond[0] §7heure(s),§c $HourMinuteSecond[1] §7minute(s),§c $HourMinuteSecond[2] §7seconde(s) avant de pouvoir reprendre un kit !");
            }
        }elseif ($player->hasPermission("champion.grade")){
            if ($timeNow - $time >= (36 * 60 * 60)) {
                switch (mt_rand(1, 3)){
                    case 1:
                        MoneyAPI::addMoney($player, 8);
                        break;
                    case 2:
                        $player->getInventory()->addItem(Item::get(Item::GOLDEN_APPLE, 0, 8));
                        break;
                    case 3:
                        Server::getInstance()->getCommandMap()->dispatch(new ConsoleCommandSender(), "givekey {$player->getName()} vote 1");
                        break;
                }
                $config->set($player->getName(), $timeNow);
            } else {
                $HourMinuteSecond = explode(":", gmdate("H:i:s", (36 * 60 * 60) - ($timeNow - $time)));
                $player->sendMessage("§f» §7Vous devez encore attendre§c $HourMinuteSecond[0] §7heure(s),§c $HourMinuteSecond[1] §7minute(s),§c $HourMinuteSecond[2] §7seconde(s) avant de pouvoir reprendre un kit !");
            }
        }elseif ($player->hasPermission("legende.grade")){
            if ($timeNow - $time >= (34 * 60 * 60)) {
                switch (mt_rand(1, 3)){
                    case 1:
                        MoneyAPI::addMoney($player, 7);
                        break;
                    case 2:
                        $player->getInventory()->addItem(Item::get(Item::GOLDEN_APPLE, 0, 7));
                        break;
                    case 3:
                        $player->getInventory()->addItem(Item::get(Item::POTION, 31, 1));
                        break;
                }
                $config->set($player->getName(), $timeNow);
            } else {
                $HourMinuteSecond = explode(":", gmdate("H:i:s", (34 * 60 * 60) - ($timeNow - $time)));
                $player->sendMessage("§f» §7Vous devez encore attendre§c $HourMinuteSecond[0] §7heure(s),§c $HourMinuteSecond[1] §7minute(s),§c $HourMinuteSecond[2] §7seconde(s) avant de pouvoir reprendre un kit !");
            }
        }elseif ($player->hasPermission("tatar.grade")){
            if ($timeNow - $time >= (32 * 60 * 60)) {
                switch (mt_rand(1, 3)){
                    case 1:
                        MoneyAPI::addMoney($player, 6);
                        break;
                    case 2:
                        $player->getInventory()->addItem(Item::get(Item::GOLDEN_APPLE, 0, 6));
                        break;
                    case 3:
                        $player->getInventory()->addItem(Item::get(Item::POTION, 31, 1));
                        break;
                }
                $config->set($player->getName(), $timeNow);
            } else {
                $HourMinuteSecond = explode(":", gmdate("H:i:s", (32 * 60 * 60) - ($timeNow - $time)));
                $player->sendMessage("§f» §7Vous devez encore attendre§c $HourMinuteSecond[0] §7heure(s),§c $HourMinuteSecond[1] §7minute(s),§c $HourMinuteSecond[2] §7seconde(s) avant de pouvoir reprendre un kit !");
            }
        }elseif ($player->hasPermission("vip.grade")){
            if ($timeNow - $time >= (28 * 60 * 60)) {
                switch (mt_rand(1, 3)){
                    case 1:
                        MoneyAPI::addMoney($player, 4);
                        break;
                    case 2:
                        $player->getInventory()->addItem(Item::get(Item::GOLDEN_APPLE, 0, 4));
                        break;
                    case 3:
                        $player->addEffect(new EffectInstance(Effect::getEffect(Effect::RESISTANCE), 20 * 60 * 3, 0, false));
                        break;
                }
                $config->set($player->getName(), $timeNow);
            } else {
                $HourMinuteSecond = explode(":", gmdate("H:i:s", (28 * 60 * 60) - ($timeNow - $time)));
                $player->sendMessage("§f» §7Vous devez encore attendre§c $HourMinuteSecond[0] §7heure(s),§c $HourMinuteSecond[1] §7minute(s),§c $HourMinuteSecond[2] §7seconde(s) avant de pouvoir reprendre un kit !");
            }
        }else{
            if ($timeNow - $time >= (24 * 60 * 60)) {
                switch (mt_rand(1, 3)){
                    case 1:
                        MoneyAPI::addMoney($player, 2);
                        break;
                    case 2:
                        $player->getInventory()->addItem(Item::get(Item::GOLDEN_APPLE, 0, 2));
                        break;
                    case 3:
                        $player->getInventory()->addItem(Item::get(Item::POTION, 14, 1));
                        break;
                }
                $config->set($player->getName(), $timeNow);
            } else {
                $HourMinuteSecond = explode(":", gmdate("H:i:s", (24 * 60 * 60) - ($timeNow - $time)));
                $player->sendMessage("§f» §7Vous devez encore attendre§c $HourMinuteSecond[0] §7heure(s),§c $HourMinuteSecond[1] §7minute(s),§c $HourMinuteSecond[2] §7seconde(s) avant de pouvoir reprendre un kit !");
            }
        }
        $player->sendMessage(Main::$prefix."Vous venez de recevoir votre récompense journalière !");
        $config->save();
    }
}