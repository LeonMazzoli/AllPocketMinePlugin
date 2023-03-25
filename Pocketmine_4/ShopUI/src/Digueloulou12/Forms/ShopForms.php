<?php

namespace Digueloulou12\Forms;

use jojoe77777\FormAPI\CustomForm;
use jojoe77777\FormAPI\SimpleForm;
use pocketmine\item\ItemFactory;
use pocketmine\player\Player;
use pocketmine\utils\Config;
use pocketmine\item\Item;
use Digueloulou12\Shop;
use pocketmine\Server;

class ShopForms
{
    public static function listCategory(Player $player, string $path = "shop"): SimpleForm
    {
        $shop = new Config(Shop::getInstance()->getDataFolder() . "config.yml", Config::YAML);
        $form = new SimpleForm(function (Player $player, $data = null) use ($shop) {
            if ($data === null) return;

            if ($shop->getNested($data . ".category")) {
                self::listCategory($player, $data);
            } else self::listItems($player, $shop->getNested($data));
        });
        $form->setTitle(Shop::getConfigReplace("title"));
        foreach ($shop->getNested($path) as $category => $key) {
            if ($category !== "category") {
                if (isset(explode("-", $category)[1])) {
                    if ($player->hasPermission(explode("-", $category)[1])) {
                        $form->addButton(explode("-", $category)[0], -1, "", $path . ".$category");
                    }
                } else $form->addButton($category, -1, "", $path . ".$category");
            }
        }
        $form->sendToPlayer($player);
        return $form;
    }

    public static function listItems(Player $player, array $array): SimpleForm
    {
        $form = new SimpleForm(function (Player $player, $data = null) {
            if (($data === null)) return;

            $i = explode(":", $data);
            self::buyAndSellItem($player, ItemFactory::getInstance()->get($i[0], $i[1]), $i[2], (float)$i[4], (float)$i[5]);
        });
        $form->setTitle(Shop::getConfigReplace("title"));
        foreach ($array as $item) {
            $i = explode(":", $item);
            if ($i[3] !== "x") $form->addButton($i[2], 0, $i[3], $item); else $form->addButton($i[2], -1, "", $item);
        }
        $form->sendToPlayer($player);
        return $form;
    }

    public static function buyAndSellItem(Player $player, Item $item, string $name, float $shop, float $sell): CustomForm
    {
        $form = new CustomForm(function (Player $player, array $data = null) use ($item, $shop, $sell, $name) {
            if ($data === null) {
                self::listCategory($player);
                return;
            }

            $api = Server::getInstance()->getPluginManager()->getPlugin(Shop::getConfigValue("money"));
            if ($data[2] !== "") {
                if (ctype_digit($data[2])) {
                    if ($data[2] > 0) {
                        if ($data[1] === 0) {
                            $money_ = intval($data[2]) * $shop;
                            if (Shop::getInstance()->getMoney($player) >= $money_) {
                                if ($player->getInventory()->canAddItem(ItemFactory::getInstance()->get($item->getId(), $item->getMeta(), intval($data[2])))) {
                                    Shop::getInstance()->removeMoney($player, $money_);
                                    $player->getInventory()->addItem(ItemFactory::getInstance()->get($item->getId(), $item->getMeta(), $data[2]));
                                    $player->sendMessage(Shop::getConfigReplace("shop_item", ["{count}", "{name}", "{money}"], [$data[2], $name, $money_]));
                                } else $player->sendMessage(Shop::getConfigReplace("inventory_full"));
                            } else $player->sendMessage(Shop::getConfigReplace("no_money"));
                        } else {
                            if ($player->getInventory()->contains(ItemFactory::getInstance()->get($item->getId(), $item->getMeta(), intval($data[2])))) {
                                $player->getInventory()->removeItem(ItemFactory::getInstance()->get($item->getId(), $item->getMeta(), intval($data[2])));

                                $money = intval($data[2]) * $sell;
                                $api->addMoney($player, $money);
                                $player->sendMessage(Shop::getConfigReplace("sell_item", ["{count}", "{name}", "{money}"], [$data[2], $name, $money]));
                            } else $player->sendMessage(Shop::getConfigReplace("no_content"));
                        }
                    } else $player->sendMessage(Shop::getConfigReplace("negative_value"));
                } else $player->sendMessage(Shop::getConfigReplace("is_numeric"));
            } else $player->sendMessage(Shop::getConfigReplace("is_numeric"));
        });
        $form->setTitle(Shop::getConfigReplace("title"));

        $count = 0;
        foreach ($player->getInventory()->getContents() as $slot => $ii) {
            if (($item->getId() === $ii->getId()) and ($item->getMeta() === $ii->getMeta())) $count += $ii->getCount();
        }

        $form->addLabel(Shop::getConfigReplace("label", ["{shop}", "{sell}", "{count}", "{name}"], [$shop, $sell, $count, $item->getName()]));
        $form->addDropdown(Shop::getConfigReplace("dropdown"), Shop::getConfigValue("dropdown_array"));
        $form->addInput(Shop::getConfigReplace("input"));
        $form->sendToPlayer($player);
        return $form;
    }
}