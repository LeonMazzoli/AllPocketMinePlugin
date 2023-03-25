<?php

namespace Digueloulou12\Forms;

use Digueloulou12\API\StaffAPI;
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
                StaffAPI::sendMessage($player, "tpgood", ["{player}"], [$random->getName()]);
                return;
            }

            $sender = Server::getInstance()->getPlayer($data);
            if ($sender instanceof Player) {
                $player->teleport($sender);
                StaffAPI::sendMessage($player, "tpgood", ["{player}"], [$data]);
            } else StaffAPI::sendMessage($player, "noplayer", ["{player}"], [$data]);
        });
        $form->setTitle(StaffAPI::getConfigValue("title"));
        $form->setContent(StaffAPI::getConfigValue("content_tp"));
        $form->addButton(StaffAPI::getConfigValue("button_random"), -1, "", "random");
        foreach (Server::getInstance()->getOnlinePlayers() as $sender) {
            if ($sender->getName() !== $player->getName()) {
                $form->addButton(str_replace(strtolower("{player}"), $sender->getName(), StaffAPI::getConfigValue("buttontp")), -1, "", $sender->getName());
            }
        }
        $form->addButton(StaffAPI::getConfigValue("backbutton"), -1, "", "back");
        $form->sendToPlayer($player);
        return $form;
    }

    public static function form(Player $player, Player $sender)
    {
        $form = new SimpleForm(function (Player $player, int $data = null) use ($sender) {
            if ($data === null) return;

            if (!($sender instanceof Player)) {
                StaffAPI::sendMessage($player, "noplayerr");
                return;
            }

            switch ($data) {
                case 0:
                    $sender->kick();
                    StaffAPI::sendMessage($player, "kickmsg", ["{player}"], [$sender->getName()]);
                    break;
                case 1:
                    $sender->kill();
                    StaffAPI::sendMessage($player, "killmsg", ["{player}"], [$sender->getName()]);
                    break;
                case 2:
                    self::infoForm($player, $sender);
                    break;
                case 3:
                    self::formInventory($player, $sender);
                    break;
                case 4:
                    if ($sender->isImmobile()) {
                        if (!empty(StaffAPI::$freeze[$sender->getName()])) unset(StaffAPI::$freeze[$sender->getName()]);
                        StaffAPI::sendMessage($player, "freeze_off", ["{player}"], [$sender->getName()]);
                        $sender->setImmobile(false);
                    } else {
                        StaffAPI::$freeze[$sender->getName()] = $sender;
                        StaffAPI::sendMessage($player, "freeze_on", ["{player}"], [$sender->getName()]);
                        $sender->setImmobile(true);
                    }
                    break;
            }
        });
        $form->setTitle(StaffAPI::getConfigValue("title"));
        $form->setContent(str_replace("{player}", $sender->getName(), StaffAPI::getConfigValue("content")));
        $form->addButton(StaffAPI::getConfigValue("button_kick"));
        $form->addButton(StaffAPI::getConfigValue("button_kill"));
        $form->addButton(StaffAPI::getConfigValue("button_info"));
        $form->addButton(StaffAPI::getConfigValue("button_inv"));
        $form->addButton(StaffAPI::getConfigValue("button_freeze"));
        $form->addButton(StaffAPI::getConfigValue("backbutton"));
        $form->sendToPlayer($player);
        return $form;
    }

    public static function infoForm(Player $player, Player $sender)
    {
        $form = new SimpleForm(function (Player $player, $data = null) use ($sender) {
            self::form($player, $sender);
        });
        $form->setTitle(StaffAPI::getConfigValue("title"));
        $replace = ["{name}", "{customname}", "{ip}", "{port}", "{gamemode}", "{x}", "{y}", "{z}", "{world}", "{ping}", "{xp}", "{xuid}"];
        $replacer = [$sender->getName(), $sender->getDisplayName(), $sender->getAddress(), $sender->getPort(), $sender->getGamemode(), $sender->getX(), $sender->getY(), $sender->getZ(), $sender->getLevel()->getName(), $sender->getPing(), $sender->getXpLevel(), $sender->getXuid()];
        $form->setContent(str_replace($replace, $replacer, StaffAPI::getConfigValue("content_info")));
        $form->addButton(StaffAPI::getConfigValue("backbutton"));
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
                StaffAPI::sendMessage($player, "noplayerr");
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
        $form->setTitle(StaffAPI::getConfigValue("title"));
        $form->setContent(StaffAPI::getConfigValue("content_inv"));
        $form->addButton(StaffAPI::getConfigValue("armor"));
        $form->addButton(StaffAPI::getConfigValue("inventory"));
        $form->addButton(StaffAPI::getConfigValue("enderchest"));
        $form->addButton(StaffAPI::getConfigValue("backbutton"));
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
                StaffAPI::sendMessage($player, "noplayerr");
                return;
            }

            $item = explode(":", $data);
            if ($player->getInventory()->canAddItem(Item::get($item[0], $item[1], $item[2]))) {
                $sender->getArmorInventory()->removeItem(Item::get($item[0], $item[1], $item[2]));
                $player->getInventory()->addItem(Item::get($item[0], $item[1], $item[2]));
            } else StaffAPI::sendMessage($player, "noplace");

        });
        $form->setTitle(StaffAPI::getConfigValue("title"));
        foreach ($sender->getArmorInventory()->getContents() as $item) {
            $form->addButton(str_replace("{item}", $item->getName(), StaffAPI::getConfigValue("button_armor")), -1, "", $item->getId() . ":" . $item->getDamage() . ":" . $item->getCount());
        }
        $form->addButton(StaffAPI::getConfigValue("backbutton"), -1, "", "back");
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
                StaffAPI::sendMessage($player, "noplayerr");
                return;
            }

            $item = explode(":", $data);
            if ($player->getInventory()->canAddItem(Item::get($item[0], $item[1], $item[2]))) {
                $sender->getInventory()->removeItem(Item::get($item[0], $item[1], $item[2]));
                $player->getInventory()->addItem(Item::get($item[0], $item[1], $item[2]));
            } else StaffAPI::sendMessage($player, "noplace");

        });
        $form->setTitle(StaffAPI::getConfigValue("title"));
        foreach ($sender->getInventory()->getContents() as $slot => $item) {
            $form->addButton(str_replace(["{slot}", "{count}", "{item}"], [$slot, $item->getCount(), $item->getName()], StaffAPI::getConfigValue("button_inv_ender")), -1, "", $item->getId() . ":" . $item->getDamage() . ":" . $item->getCount());
        }
        $form->addButton(StaffAPI::getConfigValue("backbutton"), -1, "", "back");
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
                StaffAPI::sendMessage($player, "noplayerr");
                return;
            }

            $item = explode(":", $data);
            if ($player->getInventory()->canAddItem(Item::get($item[0], $item[1], $item[2]))) {
                $sender->getEnderChestInventory()->removeItem(Item::get($item[0], $item[1], $item[2]));
                $player->getInventory()->addItem(Item::get($item[0], $item[1], $item[2]));
            } else StaffAPI::sendMessage($player, "noplace");

        });
        $form->setTitle(StaffAPI::getConfigValue("title"));
        foreach ($sender->getEnderChestInventory()->getContents() as $slot => $item) {
            $form->addButton(str_replace(["{slot}", "{count}", "{item}"], [$slot, $item->getCount(), $item->getName()], StaffAPI::getConfigValue("button_inv_ender")), -1, "", $item->getId() . ":" . $item->getDamage() . ":" . $item->getCount());
        }
        $form->addButton(StaffAPI::getConfigValue("backbutton"), -1, "", "back");
        $form->sendToPlayer($player);
        return $form;
    }
}