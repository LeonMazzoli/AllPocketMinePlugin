<?php

namespace Digueloulou12\Shop;

use jojoe77777\FormAPI\CustomForm;
use jojoe77777\FormAPI\SimpleForm;
use Digueloulou12\MultiMain;
use pocketmine\utils\Config;
use pocketmine\item\Item;
use pocketmine\Player;
use pocketmine\Server;

class ShopForms
{
    public static function listCategory(Player $player, string $shop)
    {
        $config = new Config(MultiMain::getInstance()->getDataFolder() . "config.yml", Config::YAML);
        $form = new SimpleForm(function (Player $player, $data = null) use ($shop) {
            if ($data === null) return;

            self::listItem($player, $shop, $data);
        });
        $form->setTitle($config->get("title"));
        foreach ($config->get("shop")[$shop] as $name => $item) {
            if ($name !== "type") {
                $form->addButton($name, -1, "", $name);
            }
        }
        $form->sendToPlayer($player);
        return $form;
    }

    public static function listItem(Player $player, string $shop, string $category = null)
    {
        $config = new Config(MultiMain::getInstance()->getDataFolder() . "config.yml", Config::YAML);
        $form = new SimpleForm(function (Player $player, $data = null) {
            if ($data === null) return;

            self::buyItem($player, $data);
        });
        $form->setTitle($config->get("title"));
        if ($category !== null) {
            $c = "shop.$shop.$category";
        } else $c = "shop.$shop";
        foreach ($config->getNested($c) as $item) {
            $inf = explode(":", $item);
            $form->addButton($inf[6], -1, "", $item);
        }
        $form->sendToPlayer($player);
        return $form;
    }

    public static function buyItem(Player $player, string $item)
    {
        $money = Server::getInstance()->getPluginManager()->getPlugin("EconomyAPI");
        $config = new Config(MultiMain::getInstance()->getDataFolder() . "config.yml", Config::YAML);
        $i = explode(":", $item);
        $form = new CustomForm(function (Player $player, array $data = null) use ($config, $money, $i) {
            if ($data === null) return;

            if ($data[1] === false) {
                if ((is_numeric($data[2])) and ($data[2] > 0)) {
                    $itemm = Item::get($i[0], $i[1], $i[2] * $data[2]);
                    if ($i[3] !== " ") $itemm->setCustomName($i[3]);
                    if ($player->getInventory()->canAddItem($itemm)) {
                        if ($money->myMoney($player) >= $data[2] * $i[4]) {
                            $player->getInventory()->addItem($itemm);
                            $money->reduceMoney($player, $data[2] * $i[4]);
                            $player->sendMessage(str_replace(["{count}", "{item}", "{money}"], [$i[2] * $data[2], $itemm->getName(), $data[2] * $i[4]], $config->get("buymsg")));
                        } else $player->sendMessage($config->get("nomoney"));
                    } else $player->sendMessage($config->get("noplace"));
                } else $player->sendMessage($config->get("novalid"));
            } else {
                if ((is_numeric($data[2])) and ($data[2] > 0)) {
                    $itemmm = Item::get($i[0], $i[1], $i[2] * $data[2]);
                    if ($player->getInventory()->contains($itemmm)) {
                        $player->getInventory()->removeItem($itemmm);
                        $money->addMoney($player, $data[2] * $i[5]);
                        $player->sendMessage(str_replace(["{count}", "{item}", "{money}"], [$i[2] * $data[2], $itemmm->getName(), $data[2] * $i[5]], $config->get("sellmsg")));
                    } else $player->sendMessage($config->get("nosell"));
                } else $player->sendMessage($config->get("novalid"));
            }
        });
        $form->setTitle($config->get("title"));
        $form->addLabel(str_replace(["{buy}", "{sell}", "{count}"], [$i[4], $i[5], $i[2]], $config->get("label")));
        $form->addToggle($config->get("toggle"));
        $form->addInput($config->get("input"));
        $form->sendToPlayer($player);
        return $form;
    }
}