<?php

namespace Digueloulou12\Commands;

use Digueloulou12\Main;
use pocketmine\command\CommandSender;
use pocketmine\command\PluginCommand;
use pocketmine\item\Item;
use pocketmine\Player;

class Staff extends PluginCommand
{
    public static $staff = [];
    public static $freeze = [];

    public function __construct()
    {
        $command = explode(":", Main::getConfigAPI()->getConfigValue("staff"));
        parent::__construct($command[0], Main::getInstance());
        if (isset($command[1])) $this->setDescription($command[1]);
        if (isset($command[2])) $this->setPermission($command[2]);
        $this->setAliases(Main::getConfigAPI()->getConfigValue("staff_aliases"));
    }

    public function execute(CommandSender $player, string $commandLabel, array $args)
    {
        if (!$player instanceof Player) {
            $player->sendMessage(Main::getConfigAPI()->getConfigValue("noplayer"));
            return;
        }

        if ((isset($args[0])) and ($args[0] === "help")) {
            $player->sendMessage(Main::getConfigAPI()->getConfigValue("staff_help_msg"));
            return;
        }

        $command = explode(":", Main::getConfigAPI()->getConfigValue("staff"));
        if (isset($command[2])) {
            if (!$player->hasPermission($command[2])) {
                $player->sendMessage(Main::getConfigAPI()->getConfigValue("noperm"));
                return;
            }
        }

        if (!empty(self::$staff[$player->getName()])) {
            $player->getInventory()->setContents(self::$staff[$player->getName()]["inv"]);
            $player->getArmorInventory()->setContents(self::$staff[$player->getName()]["armor"]);
            unset(self::$staff[$player->getName()]);
            $player->sendMessage(Main::getConfigAPI()->getConfigValue("staff_off"));
        } else {
            self::$staff[$player->getName()] = ["inv" => $player->getInventory()->getContents(), "armor" => $player->getArmorInventory()->getContents()];
            $player->getInventory()->clearAll();
            $player->getArmorInventory()->clearAll();
            $i = explode(":", Main::getConfigAPI()->getConfigValue("staff_item"));
            $item = Item::get($i[0], $i[1], 1);
            if (isset($i[3])) $item->setCustomName($i[3]);
            $player->getInventory()->setItem($i[2] - 1, $item);
            $player->sendMessage(Main::getConfigAPI()->getConfigValue("staff_on"));
        }
    }
}