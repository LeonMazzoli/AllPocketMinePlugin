<?php

namespace Digueloulou12\Advantages\Inventory;

use Digueloulou12\Advantages\AdvantagesGUI;
use Digueloulou12\Advantages\Utils\ItemUtils;
use Digueloulou12\Advantages\Utils\MoneyUtils;
use Digueloulou12\Advantages\Utils\Utils;
use muqsit\invmenu\InvMenu;
use muqsit\invmenu\transaction\InvMenuTransaction;
use muqsit\invmenu\transaction\InvMenuTransactionResult;
use muqsit\invmenu\type\InvMenuTypeIds;
use pocketmine\player\Player;

class AdvantagesInventory
{
    public static function sendAdvantagesInventory(Player $player): void
    {
        $data = AdvantagesGUI::getInstance()->getAdvantagesData();

        $inv = match (strtolower(Utils::getConfigValue("inventory"))) {
            "hopper" => InvMenuTypeIds::TYPE_HOPPER,
            "double" => InvMenuTypeIds::TYPE_DOUBLE_CHEST,
            default => InvMenuTypeIds::TYPE_CHEST
        };

        $menu = InvMenu::create($inv);
        $menu->setName(Utils::getConfigReplace("title"));

        foreach (Utils::getConfigValue("items") as $slot => $item) {
            $item = ItemUtils::getItemByArray($item);
            if (!is_null($item)) {
                $id = "{$item->getId()}-{$item->getMeta()}";
                if (Utils::getConfigValue("item_info")[$id]) {
                    $item->getNamedTag()->setString("effect", serialize(Utils::getConfigValue("item_info")[$id]));
                }
                $menu->getInventory()->setItem($slot, $item);
            }
        }

        $menu->setListener(function (InvMenuTransaction $transaction) use ($player, $data): InvMenuTransactionResult {
            $item = $transaction->getItemClicked();

            if (!is_null($item->getNamedTag()->getTag("effect"))) {
                $effect = ItemUtils::getEffectByArray(unserialize($item->getNamedTag()->getString("effect")));
                if (!is_null($effect)) {
                    if ($data->exists($player->getName()) and !is_null($data->getNested("{$player->getName()}." . $effect->getType()->getName()->getText()))) {
                        if ($player->getEffects()->has($effect->getType())) {
                            $player->getEffects()->remove($effect->getType());
                            $player->sendMessage(Utils::getConfigReplace("remove_good", "{name}", $effect->getType()->getName()->getText()));
                        } else {
                            $player->getEffects()->add($effect);
                            $player->sendMessage(Utils::getConfigReplace("add_effect", "{name}", $effect->getType()->getName()->getText()));
                        }
                    } else {
                        $price = $item->getNamedTag()->getInt("price");
                        if (MoneyUtils::getMoney($player) >= $price) {
                            MoneyUtils::removeMoney($player, $price);
                            $data->setNested("{$player->getName()}." . $effect->getType()->getName()->getText(), true);
                            $player->sendMessage(Utils::getConfigReplace("buy_good", "{name}", $effect->getType()->getName()->getText()));
                        } else $player->sendMessage(Utils::getConfigReplace("no_money"));
                    }
                } else $player->sendMessage(Utils::getConfigReplace("no_exist"));
            }

            return $transaction->discard();
        });

        $menu->send($player);
    }
}