<?php

namespace Digueloulou12\Commands;

use Digueloulou12\Main;
use pocketmine\command\CommandSender;
use pocketmine\command\PluginCommand;
use pocketmine\item\Item;
use pocketmine\item\ItemIds;
use pocketmine\Player;

class XpBottle extends PluginCommand
{
    public function __construct()
    {
        $command = explode(":", Main::getConfigAPI()->getConfigValue("xpbottle"));
        parent::__construct($command[0], Main::getInstance());
        if (isset($command[1])) $this->setDescription($command[1]);
        if (isset($command[2])) $this->setPermission($command[2]);
        $this->setAliases(Main::getConfigAPI()->getConfigValue("xpbottle_aliases"));
    }

    public function execute(CommandSender $player, string $commandLabel, array $args)
    {
        if (!$player instanceof Player) {
            $player->sendMessage(Main::getConfigAPI()->getConfigValue("noplayer"));
            return;
        }

        $command = explode(":", Main::getConfigAPI()->getConfigValue("xpbottle"));
        if (isset($command[2])) {
            if (!$player->hasPermission($command[2])) {
                $player->sendMessage(Main::getConfigAPI()->getConfigValue("noperm"));
                return;
            }
        }

        if (!isset($args[0])) {
            $player->sendMessage(Main::getConfigAPI()->getConfigValue("xpbottle_noargsxp"));
            return;
        }

        if ($args[0] === "all") {
            if (($player->getCurrentTotalXp() <= 15000) and ($player->getCurrentTotalXp() > 0)) {
                if ($player->getInventory()->canAddItem(Item::get(ItemIds::EXPERIENCE_BOTTLE, $player->getCurrentTotalXp(), 1))) {
                    $bottle = Item::get(ItemIds::BOTTLE_O_ENCHANTING, $player->getCurrentTotalXp(), 1)->setCustomName(Main::getConfigAPI()->getConfigValue("xpbottle_item", ["{xp}"], [$player->getCurrentTotalXp()]));
                    $player->getInventory()->addItem($bottle);
                    $player->subtractXp($player->getCurrentTotalXp());
                    $player->sendMessage(Main::getConfigAPI()->getConfigValue("xpbottle_msg"));
                } else $player->sendMessage(Main::getConfigAPI()->getConfigValue("no_place_inv"));
            } else $player->sendMessage(Main::getConfigAPI()->getConfigValue("xpbottle_novalue"));
        } else {
            if (is_numeric($args[0])) {
                if ($player->getCurrentTotalXp() >= $args[0]) {
                    if ($args[0] <= 15000) {
                        if ($player->getInventory()->addItem(Item::get(ItemIds::EXPERIENCE_BOTTLE, $args[0], 1))) {
                            $bottle = Item::get(ItemIds::BOTTLE_O_ENCHANTING, $args[0], 1)->setCustomName(Main::getConfigAPI()->getConfigValue("xpbottle_item", ["{xp}"], [$args[0]]));
                            $player->getInventory()->addItem($bottle);
                            $player->subtractXp($args[0]);
                            $player->sendMessage(Main::getConfigAPI()->getConfigValue("xpbottle_msg"));
                        } else $player->sendMessage(Main::getConfigAPI()->getConfigValue("no_place_inv"));
                    } else $player->sendMessage(Main::getConfigAPI()->getConfigValue("xpbottle_novalue"));
                } else $player->sendMessage(Main::getConfigAPI()->getConfigValue("xpbottle_noxp"));
            } else $player->sendMessage(Main::getConfigAPI()->getConfigValue("is_numeric"));
        }
    }
}