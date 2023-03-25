<?php

namespace Admin\Command;

use Admin\Menu;
use pocketmine\command\CommandSender;
use pocketmine\command\PluginCommand;
use pocketmine\item\Item;
use pocketmine\Player;

class AdminMenu extends PluginCommand
{
    public static $adminmenu = [];

    public function __construct(Menu $menu)
    {
        $command = explode(":", $menu->getConfigValue("command"));
        parent::__construct($command[0], $menu);
        if (isset($command[1])) $this->setDescription($command[1]);
        if (isset($command[2])) $this->setPermission($command[2]);
    }

    public function execute(CommandSender $player, string $commandLabel, array $args)
    {
        if (!$player instanceof Player) {
            $player->sendMessage(Menu::getInstance()->getConfigValue("console"));
            return;
        }

        $command = explode(":", Menu::getInstance()->getConfigValue("command"));
        if (isset($command[2])) {
            if (!$player->hasPermission($command[2])) {
                $player->sendMessage(Menu::getInstance()->getConfigValue("noperm"));
                return;
            }
        }

        $item = explode(":", Menu::getInstance()->getConfigValue("item"));
        if (empty(self::$adminmenu[$player->getName()])) {
            self::$adminmenu[$player->getName()] = $player->getInventory()->getContents();
            $player->getInventory()->clearAll();
            $itemm = Item::get($item[0], $item[1], 1);
            if (isset($item[3])) $itemm->setCustomName($item[3]);
            $player->getInventory()->setItem($item[2], $itemm);
            $player->sendMessage(Menu::getInstance()->getConfigValue("staffmod_on"));
        } else {
            $player->getInventory()->setContents(self::$adminmenu[$player->getName()]);
            $player->sendMessage(Menu::getInstance()->getConfigValue("staffmod_off"));
        }
    }
}