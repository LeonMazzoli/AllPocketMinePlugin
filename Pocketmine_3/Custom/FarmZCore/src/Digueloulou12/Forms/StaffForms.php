<?php

namespace Digueloulou12\Forms;

use Digueloulou12\Commands\Staff;
use Digueloulou12\Main;
use jojoe77777\FormAPI\SimpleForm;
use pocketmine\item\Item;
use pocketmine\Player;
use pocketmine\Server;

class StaffForms
{
    public static function formMain(Player $player)
    {
        $form = new SimpleForm(function (Player $player, $data = null) {
            if (($data === null) or ($data === "back")) return;

            if ($data === "random") {
                $players = Server::getInstance()->getOnlinePlayers();
                $random = $players[array_rand($players)];
                $player->teleport($random);
                $player->sendMessage(Main::getConfigAPI()->getConfigValue("tp_good", [strtolower("{player}")], [$random->getName()]));
                return;
            }

            $sender = Server::getInstance()->getPlayer($data);
            if ($sender instanceof Player) {
                $player->teleport($sender);
                $player->sendMessage(Main::getConfigAPI()->getConfigValue("tp_good", [strtolower("{player}")], [$data]));
            } else $player->sendMessage(Main::getConfigAPI()->getConfigValue("no_onlineplayer"));
        });
        $form->setTitle(Main::getConfigAPI()->getConfigValue("staff_title"));
        $form->addButton(Main::getConfigAPI()->getConfigValue("staff_random"), -1, "", "random");
        foreach (Server::getInstance()->getOnlinePlayers() as $sender) {
            if ($sender->getName() !== $player->getName()) {
                $form->addButton(str_replace(strtolower("{player}"), $sender->getName(), Main::getConfigAPI()->getConfigValue("staff_choose")), -1, "", $sender->getName());
            }
        }
        $form->addButton(Main::getConfigAPI()->getConfigValue("staff_back"), -1, "", "back");
        $form->sendToPlayer($player);
        return $form;
    }

    public static function form(Player $player, Player $sender)
    {
        $form = new SimpleForm(function (Player $player, int $data = null) use ($sender) {
            if ($data === null) return;

            if (!($sender instanceof Player)) {
                $player->sendMessage(Main::getConfigAPI()->getConfigValue("no_onlineplayer"));
                return;
            }

            switch ($data) {
                case 0:
                    $sender->kick();
                    $player->sendMessage(Main::getConfigAPI()->getConfigValue("staff_kick_msg", [strtolower("{player}")], [$sender->getName()]));
                    break;
                case 1:
                    $sender->kill();
                    $player->sendMessage(Main::getConfigAPI()->getConfigValue("staff_kill_msg", [strtolower("{player}")], [$sender->getName()]));
                    break;
                case 2:
                    self::infoForm($player, $sender);
                    break;
                case 3:
                    self::formInventory($player, $sender);
                    break;
                case 4:
                    if ($sender->isImmobile()) {
                        if (!empty(Staff::$freeze[$sender->getName()])) unset(Staff::$freeze[$sender->getName()]);
                        $player->sendMessage(Main::getConfigAPI()->getConfigValue("staff_freeze_off", [strtolower("{player}")], [$sender->getName()]));
                        $sender->setImmobile(false);
                    } else {
                        Staff::$freeze[$sender->getName()] = $sender;
                        $player->sendMessage(Main::getConfigAPI()->getConfigValue("staff_freeze_on", [strtolower("{player}")], [$sender->getName()]));
                        $sender->setImmobile(true);
                    }
                    break;
            }
        });
        $form->setTitle(Main::getConfigAPI()->getConfigValue("staff_title"));
        $form->addButton(Main::getConfigAPI()->getConfigValue("staff_button_kick"));
        $form->addButton(Main::getConfigAPI()->getConfigValue("staff_button_kill"));
        $form->addButton(Main::getConfigAPI()->getConfigValue("staff_button_info"));
        $form->addButton(Main::getConfigAPI()->getConfigValue("staff_button_inv"));
        $form->addButton(Main::getConfigAPI()->getConfigValue("staff_button_freeze"));
        $form->addButton(Main::getConfigAPI()->getConfigValue("staff_back"));
        $form->sendToPlayer($player);
        return $form;
    }

    public static function infoForm(Player $player, Player $sender)
    {
        $form = new SimpleForm(function (Player $player, $data = null) use ($sender) {
            self::form($player, $sender);
        });
        $form->setTitle(Main::getConfigAPI()->getConfigValue("staff_title"));
        $replace = ["{name}", "{customname}", "{ip}", "{port}", "{gamemode}", "{x}", "{y}", "{z}", "{world}", "{ping}", "{xp}", "{xuid}"];
        $replacer = [$sender->getName(), $sender->getDisplayName(), $sender->getAddress(), $sender->getPort(), $sender->getGamemode(), $sender->getX(), $sender->getY(), $sender->getZ(), $sender->getLevel()->getName(), $sender->getPing(), $sender->getXpLevel(), $sender->getXuid()];
        $form->setContent(Main::getConfigAPI()->getConfigValue("staff_info", $replace, $replacer));
        $form->addButton(Main::getConfigAPI()->getConfigValue("staff_back"));
        $form->sendToPlayer($player);
        return $form;
    }

    public static function formInventory(Player $player, Player $sender)
    {
        $form = new SimpleForm(function (Player $player, int $data = null) use ($sender) {
            if ($data === null) {
                self::form($player, $sender);
                return;
            }

            if (!($sender instanceof Player)) {
                $player->sendMessage(Main::getConfigAPI()->getConfigValue("no_onlineplayer"));
                return;
            }

            switch ($data) {
                case 0:
                    self::armorInventoryForm($player, $sender);
                    break;
                case 1:
                    self::inventoryForm($player, $sender);
                    break;
                case 2:
                    self::enderChestInventoryForm($player, $sender);
                    break;
                case 3:
                    self::form($player, $sender);
                    return;
            }
        });
        $form->setTitle(Main::getConfigAPI()->getConfigValue("staff_title"));
        $form->addButton(Main::getConfigAPI()->getConfigValue("staff_button_armor"));
        $form->addButton(Main::getConfigAPI()->getConfigValue("staff_button_inventory"));
        $form->addButton(Main::getConfigAPI()->getConfigValue("staff_button_enderinventory"));
        $form->addButton(Main::getConfigAPI()->getConfigValue("staff_back"));
        $form->sendToPlayer($player);
        return $form;
    }

    public static function armorInventoryForm(Player $player, Player $sender)
    {
        $form = new SimpleForm(function (Player $player, $data = null) use ($sender) {
            if (($data === null) or ($data === "back")) {
                self::formInventory($player, $sender);
                return;
            }

            if (!($sender instanceof Player)) {
                $player->sendMessage(Main::getConfigAPI()->getConfigValue("no_onlineplayer"));
                return;
            }

            $item = explode(":", $data);
            if ($player->getInventory()->canAddItem(Item::get($item[0], $item[1], $item[2]))) {
                $sender->getArmorInventory()->removeItem(Item::get($item[0], $item[1], $item[2]));
                $player->getInventory()->addItem(Item::get($item[0], $item[1], $item[2]));
            } else $player->sendMessage(Main::getConfigAPI()->getConfigValue("no_place_inv"));

        });
        $form->setTitle(Main::getConfigAPI()->getConfigValue("staff_title"));
        foreach ($sender->getArmorInventory()->getContents() as $item) {
            $form->addButton(Main::getConfigAPI()->getConfigValue("staff_armor", [strtolower("{item}")], [$item->getName()]), -1, "", $item->getId() . ":" . $item->getDamage() . ":" . $item->getCount());
        }
        $form->addButton(Main::getConfigAPI()->getConfigValue("staff_back"), -1, "", "back");
        $form->sendToPlayer($player);
        return $form;
    }

    public static function inventoryForm(Player $player, Player $sender)
    {
        $form = new SimpleForm(function (Player $player, $data = null) use ($sender) {
            if (($data === null) or ($data === "back")) {
                self::formInventory($player, $sender);
                return;
            }

            if (!($sender instanceof Player)) {
                $player->sendMessage(Main::getConfigAPI()->getConfigValue("no_onlineplayer"));
                return;
            }

            $item = explode(":", $data);
            if ($player->getInventory()->canAddItem(Item::get($item[0], $item[1], $item[2]))) {
                $sender->getInventory()->removeItem(Item::get($item[0], $item[1], $item[2]));
                $player->getInventory()->addItem(Item::get($item[0], $item[1], $item[2]));
            } else $player->sendMessage(Main::getConfigAPI()->getConfigValue("no_place_inv"));

        });
        $form->setTitle(Main::getConfigAPI()->getConfigValue("staff_title"));
        foreach ($sender->getInventory()->getContents() as $slot => $item) {
            $form->addButton(Main::getConfigAPI()->getConfigValue("staff_enderinvsee", ["{slot}", "{count}", "{item}"], [$slot, $item->getCount(), $item->getName()]), -1, "", $item->getId() . ":" . $item->getDamage() . ":" . $item->getCount());
        }
        $form->addButton(Main::getConfigAPI()->getConfigValue("staff_back"), -1, "", "back");
        $form->sendToPlayer($player);
        return $form;
    }

    public static function enderChestInventoryForm(Player $player, Player $sender)
    {
        $form = new SimpleForm(function (Player $player, $data = null) use ($sender) {
            if (($data === null) or ($data === "back")) {
                self::formInventory($player, $sender);
                return;
            }

            if (!($sender instanceof Player)) {
                $player->sendMessage(Main::getConfigAPI()->getConfigValue("no_onlineplayer"));
                return;
            }

            $item = explode(":", $data);
            if ($player->getInventory()->canAddItem(Item::get($item[0], $item[1], $item[2]))) {
                $sender->getEnderChestInventory()->removeItem(Item::get($item[0], $item[1], $item[2]));
                $player->getInventory()->addItem(Item::get($item[0], $item[1], $item[2]));
            } else $player->sendMessage(Main::getConfigAPI()->getConfigValue("no_place_inv"));

        });
        $form->setTitle(Main::getConfigAPI()->getConfigValue("staff_title"));
        foreach ($sender->getEnderChestInventory()->getContents() as $slot => $item) {
            $form->addButton(Main::getConfigAPI()->getConfigValue("staff_enderinvsee", ["{slot}", "{count}", "{item}"], [$slot, $item->getCount(), $item->getName()]), -1, "", $item->getId() . ":" . $item->getDamage() . ":" . $item->getCount());
        }
        $form->addButton(Main::getConfigAPI()->getConfigValue("staff_back"), -1, "", "back");
        $form->sendToPlayer($player);
        return $form;
    }
}