<?php

namespace Digueloulou12;

use pocketmine\command\ConsoleCommandSender;
use pocketmine\item\enchantment\Enchantment;
use pocketmine\item\enchantment\EnchantmentInstance;
use pocketmine\item\Item;
use pocketmine\Player;
use pocketmine\Server;

class DuelAPI{
    public static $invitation = [];
    public static $inventory = [];
    public static $armorinv = [];
    public static $players = [];
    public static $god = [];

    public static function startGame(Player $player, Player $sender)
    {
        self::$inventory[$player->getName()] = $player->getInventory()->getContents();
        self::$inventory[$sender->getName()] = $sender->getInventory()->getContents();
        self::$armorinv[$player->getName()] = $player->getArmorInventory()->getContents();
        self::$armorinv[$sender->getName()] = $sender->getArmorInventory()->getContents();
        $player->getInventory()->clearAll();
        $sender->getInventory()->clearAll();
        $player->getArmorInventory()->clearAll();
        $sender->getArmorInventory()->clearAll();
        self::$players[$player->getName()] = $player;
        self::$players[$sender->getName()] = $sender;
        $player->sendMessage(MainDuel::$config->get("start"));
        $sender->sendMessage(MainDuel::$config->get("start"));
        new DuelTask($player, $sender);
    }

    public static function sendInvitation(Player $player, Player $sender){
        $player->sendMessage(str_replace(strtolower("{player}"), $sender->getName(), MainDuel::$config->get("invitation")));
        self::$invitation[$player->getName()] = ["time" => time() + MainDuel::$config->get("invitation_expire"), "player" => $sender->getName()];
    }

    public static function stopGame()
    {
        foreach (self::$players as $name => $player) {
            if ($player instanceof Player) {
                $player->getArmorInventory()->clearAll();
                $player->getInventory()->clearAll();
                if (!empty(self::$inventory[$name])) {
                    $player->getInventory()->setContents(self::$inventory[$player->getName()]);
                    unset(self::$inventory[$name]);
                }

                if (!empty(self::$armorinv[$player->getName()])){
                    $player->getArmorInventory()->setContents(self::$armorinv[$player->getName()]);
                    unset(self::$armorinv[$player->getName()]);
                }

                if (!empty(self::$god[$name])) unset(self::$god[$name]);
                if (!empty(self::$players[$name])) unset(self::$players[$name]);

                $player->setImmobile(false);

                $player->teleport(Server::getInstance()->getDefaultLevel()->getSpawnLocation());
                $player->sendMessage(MainDuel::$config->get("stop"));
            }
        }
    }

    public static function finishGame(Player $winner){
        foreach (self::$players as $name => $player){
            if ($player instanceof Player){
                $player->getArmorInventory()->clearAll();
                $player->getInventory()->clearAll();
                if (!empty(self::$inventory[$name])) {
                    $player->getInventory()->setContents(self::$inventory[$player->getName()]);
                    unset(self::$inventory[$name]);
                }

                if (!empty(self::$armorinv[$player->getName()])){
                    $player->getArmorInventory()->setContents(self::$armorinv[$player->getName()]);
                    unset(self::$armorinv[$player->getName()]);
                }

                if (!empty(self::$god[$name])) unset(self::$god[$name]);
                if (!empty(self::$players[$name])) unset(self::$players[$name]);


                $player->teleport(Server::getInstance()->getDefaultLevel()->getSpawnLocation());
                $player->sendMessage(str_replace(strtolower("{player}"), $winner->getName(), MainDuel::$config->get("win")));
            }
        }
        Server::getInstance()->getCommandMap()->dispatch(new ConsoleCommandSender(), str_replace(strtolower("{player}"), $winner->getName(), MainDuel::$config->get("command_win")));
    }

    public static function setKit(Player $player){
        foreach (MainDuel::$config->get("items") as $config){
            $item = explode(":", $config);
            $item_return = Item::get($item[0], $item[1], $item[2]);
            if ((isset($item[3])) and ($item[3] !== " ")) $item_return->setCustomName($item[3]);
            if (isset($item[4]) and isset($item[5])) {
                $enchant = new EnchantmentInstance(Enchantment::getEnchantment($item[4]), $item[5]);
                $item_return->addEnchantment($enchant);
            }
            $player->getInventory()->addItem($item_return);
        }

        if (MainDuel::$config->get("helmet") !== null) {
            if ($player->getArmorInventory()->getHelmet()->isNull()) {
                $helmet = explode(":", MainDuel::$config->get("helmet"));
                $helmet_item = Item::get($helmet[0], $helmet[1], 1);
                if ((isset($helmet[3])) and ($helmet[3] !== " ")) $helmet_item->setCustomName($helmet[3]);
                if (isset($helmet[4]) and isset($helmet[5])) {
                    $enchant = new EnchantmentInstance(Enchantment::getEnchantment($helmet[4]), $helmet[5]);
                    $helmet_item->addEnchantment($enchant);
                }
                $player->getArmorInventory()->setHelmet($helmet_item);
            }
        }

        if (MainDuel::$config->get("chestplace") !== null) {
            if ($player->getArmorInventory()->getChestplate()->isNull()) {
                $chestplate = explode(":", MainDuel::$config->get("chestplate"));
                $chestplate_item = Item::get($chestplate[0], $chestplate[1], 1);
                if ((isset($chestplate[3])) and ($chestplate[3] !== " ")) $chestplate_item->setCustomName($chestplate[3]);
                if (isset($chestplate[4]) and isset($chestplate[5])) {
                    $enchant = new EnchantmentInstance(Enchantment::getEnchantment($chestplate[4]), $chestplate[5]);
                    $chestplate_item->addEnchantment($enchant);
                }
                $player->getArmorInventory()->setChestplate($chestplate_item);
            }
        }

        if (MainDuel::$config->get("leggings") !== null) {
            if ($player->getArmorInventory()->getLeggings()->isNull()) {
                $leggins = explode(":", MainDuel::$config->get("leggings"));
                $leggins_item = Item::get($leggins[0], $leggins[1], 1);
                if ((isset($leggins[3])) and ($leggins[3] !== " ")) $leggins_item->setCustomName($leggins[3]);
                if (isset($leggins[4]) and isset($leggins[5])) {
                    $enchant = new EnchantmentInstance(Enchantment::getEnchantment($leggins[4]), $leggins[5]);
                    $leggins_item->addEnchantment($enchant);
                }
                $player->getArmorInventory()->setLeggings($leggins_item);
            }
        }

        if (MainDuel::$config->get("boots") !== null) {
            if ($player->getArmorInventory()->getBoots()->isNull()) {
                $boots = explode(":", MainDuel::$config->get("boots"));
                $boots_item = Item::get($boots[0], $boots[1], 1);
                if ((isset($boots[3])) and ($boots[3] !== " ")) $boots_item->setCustomName($boots[3]);
                if (isset($boots[4]) and isset($boots[5])) {
                    $enchant = new EnchantmentInstance(Enchantment::getEnchantment($boots[4]), $boots[5]);
                    $boots_item->addEnchantment($enchant);
                }
                $player->getArmorInventory()->setBoots($boots_item);
            }
        }
    }
}