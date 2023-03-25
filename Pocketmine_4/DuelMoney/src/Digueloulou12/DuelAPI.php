<?php

namespace Digueloulou12;

use pocketmine\item\enchantment\EnchantmentInstance;
use pocketmine\item\enchantment\VanillaEnchantments;
use pocketmine\console\ConsoleCommandSender;
use pocketmine\item\ItemFactory;
use pocketmine\lang\Language;
use pocketmine\player\Player;
use pocketmine\Server;

class DuelAPI
{
    public static array $invitation = [];
    public static array $inventory = [];
    public static array $armorinv = [];
    public static array $players = [];
    public static array $money = [];
    public static array $god = [];

    public static function startGame(Player $player, Player $sender, int $money)
    {
        foreach ([$player, $sender] as $user) {
            self::$money[$user->getName()] = $money;
            self::$inventory[$user->getName()] = $user->getInventory()->getContents();
            self::$armorinv[$user->getName()] = $user->getArmorInventory()->getContents();
            $user->getInventory()->clearAll();
            $user->getArmorInventory()->clearAll();
            self::$players[$user->getName()] = $user;
            $user->sendMessage(MainDuel::$config->get("start"));
            Server::getInstance()->getPluginManager()->getPlugin("EconomyAPI")->reduceMoney($user, $money);
        }

        new DuelTask($player, $sender);
    }

    public static function sendInvitation(Player $player, Player $sender, int $money)
    {
        $player->sendMessage(str_replace(["{player}", "{money}"], [$sender->getName(), $money], MainDuel::$config->get("invitation")));
        self::$invitation[$player->getName()] = ["time" => time() + MainDuel::$config->get("invitation_expire"), "player" => $sender->getName(), "money" => $money];
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

                if (!empty(self::$armorinv[$player->getName()])) {
                    $player->getArmorInventory()->setContents(self::$armorinv[$player->getName()]);
                    unset(self::$armorinv[$player->getName()]);
                }

                if (!empty(self::$god[$name])) unset(self::$god[$name]);
                if (!empty(self::$players[$name])) unset(self::$players[$name]);

                $player->setImmobile(false);

                $player->teleport(Server::getInstance()->getWorldManager()->getDefaultWorld()->getSafeSpawn());
                $player->sendMessage(MainDuel::$config->get("stop"));
            }
        }
    }

    public static function finishGame(Player $winner)
    {
        foreach (self::$players as $name => $player) {
            if ($player instanceof Player) {
                $player->getArmorInventory()->clearAll();
                $player->getInventory()->clearAll();
                if (!empty(self::$inventory[$name])) {
                    $player->getInventory()->setContents(self::$inventory[$player->getName()]);
                    unset(self::$inventory[$name]);
                }

                if (!empty(self::$armorinv[$player->getName()])) {
                    $player->getArmorInventory()->setContents(self::$armorinv[$player->getName()]);
                    unset(self::$armorinv[$player->getName()]);
                }

                Server::getInstance()->getPluginManager()->getPlugin("EconomyAPI")->addMoney($winner, self::$money[$player->getName()]);
                unset(self::$money[$player->getName()]);

                if (!empty(self::$god[$name])) unset(self::$god[$name]);
                if (!empty(self::$players[$name])) unset(self::$players[$name]);


                $player->teleport(Server::getInstance()->getWorldManager()->getDefaultWorld()->getSafeSpawn());
                $player->sendMessage(str_replace(strtolower("{player}"), $winner->getName(), MainDuel::$config->get("win")));
            }
        }
        Server::getInstance()->getCommandMap()->dispatch(new ConsoleCommandSender(Server::getInstance(), new Language(Language::FALLBACK_LANGUAGE)), str_replace(strtolower("{player}"), $winner->getName(), MainDuel::$config->get("command_win")));
    }

    public static function setKit(Player $player)
    {
        foreach (MainDuel::$config->get("items") as $config) {
            $item = explode(":", $config);
            $item_return = ItemFactory::getInstance()->get($item[0], $item[1], $item[2]);
            if ((isset($item[3])) and ($item[3] !== " ")) $item_return->setCustomName($item[3]);
            if (isset($item[4]) and isset($item[5])) {
                $enchant = new EnchantmentInstance(VanillaEnchantments::fromString($item[4]), $item[5]);
                $item_return->addEnchantment($enchant);
            }
            $player->getInventory()->addItem($item_return);
        }

        if (MainDuel::$config->get("helmet") !== null) {
            if ($player->getArmorInventory()->getHelmet()->isNull()) {
                $helmet = explode(":", MainDuel::$config->get("helmet"));
                $helmet_item = ItemFactory::getInstance()->get($helmet[0], $helmet[1], 1);
                if ((isset($helmet[3])) and ($helmet[3] !== " ")) $helmet_item->setCustomName($helmet[3]);
                if (isset($helmet[4]) and isset($helmet[5])) {
                    $enchant = new EnchantmentInstance(VanillaEnchantments::fromString($helmet[4]), $helmet[5]);
                    $helmet_item->addEnchantment($enchant);
                }
                $player->getArmorInventory()->setHelmet($helmet_item);
            }
        }

        if (MainDuel::$config->get("chestplace") !== null) {
            if ($player->getArmorInventory()->getChestplate()->isNull()) {
                $chestplate = explode(":", MainDuel::$config->get("chestplate"));
                $chestplate_item = ItemFactory::getInstance()->get($chestplate[0], $chestplate[1], 1);
                if ((isset($chestplate[3])) and ($chestplate[3] !== " ")) $chestplate_item->setCustomName($chestplate[3]);
                if (isset($chestplate[4]) and isset($chestplate[5])) {
                    $enchant = new EnchantmentInstance(VanillaEnchantments::fromString($chestplate[4]), $chestplate[5]);
                    $chestplate_item->addEnchantment($enchant);
                }
                $player->getArmorInventory()->setChestplate($chestplate_item);
            }
        }

        if (MainDuel::$config->get("leggings") !== null) {
            if ($player->getArmorInventory()->getLeggings()->isNull()) {
                $leggins = explode(":", MainDuel::$config->get("leggings"));
                $leggins_item = ItemFactory::getInstance()->get($leggins[0], $leggins[1], 1);
                if ((isset($leggins[3])) and ($leggins[3] !== " ")) $leggins_item->setCustomName($leggins[3]);
                if (isset($leggins[4]) and isset($leggins[5])) {
                    $enchant = new EnchantmentInstance(VanillaEnchantments::fromString($leggins[4]), $leggins[5]);
                    $leggins_item->addEnchantment($enchant);
                }
                $player->getArmorInventory()->setLeggings($leggins_item);
            }
        }

        if (MainDuel::$config->get("boots") !== null) {
            if ($player->getArmorInventory()->getBoots()->isNull()) {
                $boots = explode(":", MainDuel::$config->get("boots"));
                $boots_item = ItemFactory::getInstance()->get($boots[0], $boots[1], 1);
                if ((isset($boots[3])) and ($boots[3] !== " ")) $boots_item->setCustomName($boots[3]);
                if (isset($boots[4]) and isset($boots[5])) {
                    $enchant = new EnchantmentInstance(VanillaEnchantments::fromString($boots[4]), $boots[5]);
                    $boots_item->addEnchantment($enchant);
                }
                $player->getArmorInventory()->setBoots($boots_item);
            }
        }
    }
}