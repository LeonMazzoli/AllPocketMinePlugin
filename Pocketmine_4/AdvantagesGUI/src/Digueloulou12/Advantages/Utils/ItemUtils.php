<?php

namespace Digueloulou12\Advantages\Utils;

use pocketmine\data\bedrock\EffectIdMap;
use pocketmine\entity\effect\Effect;
use pocketmine\entity\effect\EffectInstance;
use pocketmine\item\Item;
use pocketmine\item\ItemFactory;

class ItemUtils
{
    public static function getItemByArray(array $array): ?Item
    {
        if (isset($array[0]) and ctype_alnum(intval($array[0]))) {
            $item = ItemFactory::getInstance()->get(intval($array[0]), intval($array[1]) ?? 0, intval($array[2]) ?? 1);
            $item->getNamedTag()->setInt("price", $array[3] ?? 0);
            if (isset($array[4])) $item->setCustomName($array[4]);
            return $item;
        }
        return null;
    }

    public static function getEffectByArray(array $array): ?EffectInstance
    {
        if (isset($array[0]) and is_int(intval($array[0]))) {
            return new EffectInstance(EffectIdMap::getInstance()->fromId(intval($array[0])), 20 * intval($array[1]) ?? 60, intval($array[2]) ?? 0, boolval($array[3]) ?? false);
        }
        return null;
    }
}