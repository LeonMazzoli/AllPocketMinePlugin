<?php

namespace Digueloulou12;

use pocketmine\item\enchantment\EnchantmentInstance;
use pocketmine\data\bedrock\EnchantmentIdMap;
use Digueloulou12\Events\SwordEvents;
use pocketmine\plugin\PluginBase;
use pocketmine\item\ItemFactory;
use pocketmine\player\Player;
use pocketmine\item\Item;

class RyanSword extends PluginBase
{
    private static RyanSword $ryanSword;

    public function onEnable(): void
    {
        self::$ryanSword = $this;
        $this->getServer()->getPluginManager()->registerEvents(new SwordEvents(), $this);
    }

    public static function getRyanSword(): RyanSword
    {
        return self::$ryanSword;
    }

    public function takeKit(Player $player): void
    {
        $player->getInventory()->clearAll();
        $player->getArmorInventory()->clearAll();

        if ($this->getConfig()->get("kit")["helmet"] !== null) $player->getArmorInventory()->setHelmet($this->getItem($this->getConfig()->get("kit")["helmet"]));
        if ($this->getConfig()->get("kit")["chestplate"] !== null) $player->getArmorInventory()->setHelmet($this->getItem($this->getConfig()->get("kit")["chestplate"]));
        if ($this->getConfig()->get("kit")["leggings"] !== null) $player->getArmorInventory()->setHelmet($this->getItem($this->getConfig()->get("kit")["leggings"]));
        if ($this->getConfig()->get("kit")["boots"] !== null) $player->getArmorInventory()->setHelmet($this->getItem($this->getConfig()->get("kit")["boots"]));

        foreach ($this->getConfig()->get("kit")["items"] as $item) {
            $player->getInventory()->addItem($this->getItem($item));
        }
    }

    public function getItem(array $array): ?Item
    {
        if ((is_numeric($array[0])) and (is_numeric($array[1])) and (is_numeric($array[2]))) {
            $item = ItemFactory::getInstance()->get($array[0], $array[1], $array[2]);
            if ((isset($array[3])) and (is_array($array[3]))) {
                foreach ($array[3] as $enchant) {
                    if ((is_numeric($enchant[0])) and (is_numeric($enchant[1]))) {
                        $item->addEnchantment(new EnchantmentInstance(EnchantmentIdMap::getInstance()->fromId($enchant[0]), $enchant[1]));
                    }
                }
                return $item;
            } else return $item;
        } else return null;
    }
}