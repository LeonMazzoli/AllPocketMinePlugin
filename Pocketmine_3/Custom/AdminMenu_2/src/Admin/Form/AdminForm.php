<?php

namespace Admin\Form;

use Admin\Menu;
use jojoe77777\FormAPI\CustomForm;
use jojoe77777\FormAPI\SimpleForm;
use pocketmine\Player;
use pocketmine\Server;
use pocketmine\utils\Config;

class AdminForm
{
    public static $mute = [];

    public static function AdminPlayerTp($player)
    {
        $form = new SimpleForm(function (Player $player, $data = null) {
            $result = $data;
            if ($result === null) {
                return true;
            }
            $sender = Server::getInstance()->getPlayer($result);
            if ($sender instanceof Player) {
                $pos = $sender->getPosition();
                $player->teleport($pos);
                $player->sendMessage(Menu::getInstance()->getConfigValue("tpgood"));
            } else $player->sendMessage(Menu::getInstance()->getConfigValue("tpno"));
        });
        $form->setTitle(Menu::getInstance()->getConfigValue("titletp"));
        $form->setContent(Menu::getInstance()->getConfigValue("contenttp"));
        foreach (Server::getInstance()->getOnlinePlayers() as $online) {
            $form->addButton($online->getName(), -1, "", $online->getName());
        }
        $form->sendToPlayer($player);
        return $form;
    }

    public static function ActionPlayer(Player $player, $sender)
    {
        $form = new SimpleForm(function (Player $player, int $data = null) use ($sender) {
            $result = $data;
            if ($result == null) {
                return false;
            }
            switch ($result) {
                case 0:
                    if ($sender instanceof Player) {
                        $player->sendMessage(str_replace(["{ping}", "{player}"], [$sender->getPing(), $sender->getName()], Menu::getConfigValue("pingmsg")));
                    }
                    break;
                case 1:
                    if ($sender instanceof Player) {
                        if ($sender->isImmobile()) {
                            $sender->setImmobile(false);
                            $player->sendMessage(str_replace("{player}", $sender->getName(), Menu::getConfigValue("freezeoff")));
                            $sender->sendMessage()
                        } else {
                            $sender->setImmobile(true);
                            $player->sendMessage(str_replace("{player}", $sender->getName(), $config->get("freezeyes")));
                        }
                    }
                    break;
                case 2:
                    if ($sender instanceof Player) {
                        $sender->kill();
                        $player->sendMessage(str_replace("{player}", $sender->getName(), $config->get("killgood")));
                    }
                    break;
                case 3:
                    if ($sender instanceof Player){
                        self::Kick($player, $sender);
                    }
                    break;
                case 4:
                    if ($sender instanceof Player){
                        if (!isset(self::$mute[$sender->getName()])){
                            self::Mute($player, $sender);
                        }else{
                            unset(self::$mute[$sender->getName()]);
                            $player->sendMessage(Menu::getInstance()->getConfigValue("unmute"));
                            $sender->sendMessage(Menu::getInstance()->getConfigValue("unmutep"));
                        }
                    }
                    break;
            }
        });
        $form->setTitle($config->get("titlepl"));
        $form->setContent($config->get("contentpl"));
        $form->addButton($config->get("ping"));
        $form->addButton($config->get("freeze"));
        $form->addButton($config->get("kill"));
        $form->addButton($config->get("kick"));
        $form->addButton($config->get("mute"));
        $form->sendToPlayer($player);
        return $form;
    }

    public static function Kick($player, $sender)
    {
        $config = new Config(Menu::getInstance()->getDataFolder() . "config.yml", Config::YAML);
        $form = new CustomForm(function (Player $player, array $data = null) use ($config, $sender) {
            if ($data === null) {
                return true;
            }
                if ($sender instanceof Player) {
                    if ($data[1] != null) {
                        $sender->kick("$data[1]", false);
                        $player->sendMessage(str_replace("{player}", $sender->getName(), $config->get("kickgood")));
                    } else $player->sendMessage($config->get("kickr"));
                }
        });

        $form->setTitle($config->get("titlek"));
        $form->addLabel($config->get("contentk"));
        $form->addInput($config->get("raisonk"));
        $form->sendToPlayer($player);
        return $form;
    }


    public static function Mute($player, $sender)
    {
        $config = new Config(Menu::getInstance()->getDataFolder() . "config.yml", Config::YAML);
        $form = new CustomForm(function (Player $player, array $data = null) use ($config, $sender) {
            if ($data === null) {
                return true;
            }
            if ($sender instanceof Player) {
                $num = $data[1] * 60;
                if (empty(self::$mute[$sender->getName()]) or self::$mute[$sender->getName()] <= time()) {
                    self::$mute[$sender->getName()] = time() + $num;
                    $sender->sendMessage(str_replace("{time}", $data[1], $config->get("mutepl")));
                    $player->sendMessage($config->get("mutepla"));
                } else $sender->sendMessage($config->get("almute"));
            }
        });

        $form->setTitle($config->get("titlem"));
        $form->addLabel($config->get("contentm"));
        $form->addSlider($config->get("timemute"), $config->get("mutemin"), $config->get("mutemax"));
        $form->sendToPlayer($player);
        return $form;
    }
}