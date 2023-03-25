<?php

namespace Digueloulou12;

use pocketmine\item\enchantment\EnchantmentInstance;
use pocketmine\data\bedrock\EnchantmentIdMap;
use Digueloulou12\Command\KitCommand;
use pocketmine\plugin\PluginBase;
use pocketmine\item\ItemFactory;
use pocketmine\player\Player;
use pocketmine\utils\Config;
use pocketmine\item\Item;
use pocketmine\Server;

class Kits extends PluginBase
{
    private static Config $time;
    private static Kits $kits;

    public function onEnable(): void
    {
        self::$kits = $this;
        $this->saveDefaultConfig();

        $eco = $this->getConfig()->get("eco");
        if (Server::getInstance()->getPluginManager()->getPlugin($eco) === null) {
            Server::getInstance()->getLogger()->alert("DISABLE PLUGIN KITS, NO FOUND PLUGIN $eco !");
            Server::getInstance()->getPluginManager()->disablePlugin($this);
            return;
        }

        self::$time = new Config($this->getDataFolder() . "Time.json", Config::JSON);

        $this->getServer()->getCommandMap()->register("", new KitCommand());
    }

    public static function getInstance(): Kits
    {
        return self::$kits;
    }

    public function getTime(Player $player, string $kit): int
    {
        if (self::$time->getNested($player->getName() . ".$kit") !== null) {
            return self::$time->get($player->getName())[$kit];
        } else return 0;
    }

    public function setTime(Player $player, string $kit, int $time): void
    {
        self::$time->setNested($player->getName() . ".$kit", time() + $time);
        self::$time->save();
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