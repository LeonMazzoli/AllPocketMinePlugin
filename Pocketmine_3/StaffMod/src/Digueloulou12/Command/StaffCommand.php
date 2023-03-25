<?php

namespace Digueloulou12\Command;

use Digueloulou12\API\StaffAPI;
use Digueloulou12\MainStaff;
use pocketmine\command\CommandSender;
use pocketmine\command\PluginCommand;
use pocketmine\item\Item;
use pocketmine\Player;

class StaffCommand extends PluginCommand{
    public function __construct(MainStaff $mainStaff)
    {
        $command = explode(":", StaffAPI::getConfigValue("command"));
        parent::__construct($command[0], $mainStaff);
        if (isset($command[1])) $this->setDescription($command[1]);
        if (isset($command[2])) $this->setPermission($command[2]);
        $this->setAliases(StaffAPI::getConfigValue("command_aliases"));
    }

    public function execute(CommandSender $player, string $commandLabel, array $args)
    {
        if (!($player instanceof Player)){
            $player->sendMessage(StaffAPI::getConfigValue("useig"));
            return;
        }

        $command = explode(":", StaffAPI::getConfigValue("command"));
        if (isset($command[2])){
            if (!$player->hasPermission($command[2])){
                StaffAPI::sendMessage($player, "noperm", ["{perm}"], [$command[2]]);
                return;
            }
        }

        if (StaffAPI::isStaffMod($player)){
            $player->getInventory()->setContents(StaffAPI::$staff[$player->getName()]["inv"]);
            $player->getArmorInventory()->setContents(StaffAPI::$staff[$player->getName()]["armor"]);
            unset(StaffAPI::$staff[$player->getName()]);
            StaffAPI::sendMessage($player, "staff_off");
        }else{
            StaffAPI::$staff[$player->getName()] = ["inv" => $player->getInventory()->getContents(), "armor" => $player->getArmorInventory()->getContents()];
            $player->getInventory()->clearAll();
            $player->getArmorInventory()->clearAll();
            $i = explode(":", StaffAPI::getConfigValue("item"));
            $item = Item::get($i[0], $i[1], 1);
            if (isset($i[3])) $item->setCustomName($i[3]);
            $player->getInventory()->setItem($i[2], $item);
            StaffAPI::sendMessage($player, "staff_on");
        }
    }
}