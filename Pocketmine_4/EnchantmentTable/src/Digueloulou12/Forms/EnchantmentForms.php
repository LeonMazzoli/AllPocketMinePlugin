<?php

namespace Digueloulou12\Forms;

use pocketmine\item\enchantment\EnchantmentInstance;
use pocketmine\data\bedrock\EnchantmentIdMap;
use jojoe77777\FormAPI\CustomForm;
use jojoe77777\FormAPI\SimpleForm;
use pocketmine\item\ItemFactory;
use pocketmine\player\Player;
use pocketmine\item\ItemIds;
use Digueloulou12\Table;

class EnchantmentForms
{
    public static function listEnchants(Player $player, array $enchants): SimpleForm
    {
        $form = new SimpleForm(function (Player $player, $data = null) {
            if ($data === null) return;

            $player->sendForm(self::enchantForm($data));
        });
        $form->setTitle(Table::getInstance()->getConfig()->get("title"));
        foreach ($enchants as $enchant) {
            $name = $player->getLanguage()->translate(EnchantmentIdMap::getInstance()->fromId(explode(":", $enchant)[0])->getName());
            $form->addButton($name, -1, "", $enchant);
        }
        return $form;
    }

    public static function enchantForm(string $en): CustomForm
    {
        $enchant = explode(":", $en);
        $form = new CustomForm(function (Player $player, array $data = null) use ($enchant) {
            if ($data === null) return;

            $config = Table::getInstance()->getConfig();

            $enchantment = EnchantmentIdMap::getInstance()->fromId($enchant[0]);
            if (Table::getInstance()->getMoney($player) >= ($enchant[2] * $data[1])) {
                if ($enchant[3] != 0) {
                    if (!$player->getInventory()->contains(ItemFactory::getInstance()->get(ItemIds::DYE, 4, $enchant[3] * $data[1]))) {
                        $player->sendMessage($config->get("no_lapis"));
                        return;
                    }
                }

                if ($player->getXpManager()->getXpLevel() >= ($enchant[4] * $data[1])) {
                    $item = $player->getInventory()->getItemInHand();
                    $level = 0;
                    if ($item->hasEnchantment($enchantment)) {
                        if (($item->getEnchantment($enchantment)->getLevel() + $data[1]) > $enchant[1]) {
                            $player->sendMessage(Table::getInstance()->getConfig()->get("no_enchant"));
                            return;
                        } else $level = $item->getEnchantment($enchantment)->getLevel();
                    }

                    $item->addEnchantment(new EnchantmentInstance($enchantment, $level + $data[1]));
                    $player->getInventory()->setItemInHand($item);

                    Table::getInstance()->removeMoney($player, $enchant[2] * $data[1]);
                    $player->getInventory()->removeItem(ItemFactory::getInstance()->get(ItemIds::DYE, 4, $enchant[3] * $data[1]));
                    $player->getXpManager()->subtractXpLevels($enchant[4] * $data[1]);
                } else $player->sendMessage($config->get("no_xp"));
            } else $player->sendMessage($config->get("no_money"));
        });
        $form->setTitle(Table::getInstance()->getConfig()->get("title"));
        $form->addLabel(str_replace(["{xp}", "{money}", "{lapis}"], [$enchant[4], $enchant[2], $enchant[3]], Table::getInstance()->getConfig()->get("label")));
        $form->addSlider("Niveau(x)", 1, $enchant[1]);
        return $form;
    }
}