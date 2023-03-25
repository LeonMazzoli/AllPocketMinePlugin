<?php

namespace Digueloulou12\InventorySynchronizer\API;

use Digueloulou12\InventorySynchronizer\Utils\Utils;
use pocketmine\item\Item;

class DatabaseAPI
{
    public static function init()
    {
        $db = self::getDatabase();
        $db->query("CREATE TABLE IF NOT EXISTS inventories(name VARCHAR(255), inventoryContents TEXT, armorInventoryContents TEXT)");
        $db->close();
    }

    public static function update(string $name, string $inventory, string $armorInventory, bool $register = false): void
    {
        $db = self::getDatabase();
        $register ? $db->query("INSERT INTO inventories(name, inventoryContents, armorInventoryContents) VALUES ('$name', '$inventory', '$armorInventory')") :
            $db->query("UPDATE inventories SET inventoryContents='$inventory', armorInventoryContents='$armorInventory' WHERE name='$name'");
        $db->close();
    }

    public static function getInventoryContents(string $name, bool $armor = false): array
    {
        $db = self::getDatabase();
        $res = $db->query("SELECT * FROM inventories WHERE name='$name'");
        $db->close();
        $array = $res->fetch_array();
        $str = base64_decode($armor ? $array["armorInventoryContents"] :$array["inventoryContents"]);
        $inv = unserialize($str);

        $return = [];
        foreach ($inv as $slot => $item) {
            $return[$slot] = Item::jsonDeserialize($item);
        }
        return $return;
    }

    public static function existInventory(string $name): bool
    {
        $db = self::getDatabase();
        $res = $db->query("SELECT * FROM inventories WHERE name='$name'");
        $db->close();

        return $res->num_rows > 0;
    }

    public static function getDatabase(): \mysqli
    {
        return new \MySQLi(Utils::getConfigValue("mysql-host"), Utils::getConfigValue("mysql-user"), Utils::getConfigValue("mysql-password"), Utils::getConfigValue("mysql-database"));
    }
}