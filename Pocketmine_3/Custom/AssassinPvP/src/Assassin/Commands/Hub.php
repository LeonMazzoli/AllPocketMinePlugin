<?php

namespace Assassin\Commands;

use Assassin\Main;
use pocketmine\command\CommandSender;
use pocketmine\command\PluginCommand;
use pocketmine\item\Item;
use pocketmine\level\Position;
use pocketmine\Player;
use pocketmine\Server;
use pocketmine\utils\Config;

class Hub extends PluginCommand{
    private $main;
    public function __construct(Main $main)
    {
        parent::__construct("hub", $main);
        $this->setDescription("Téléporte au hub");
        $this->main = $main;
    }

    public function execute(CommandSender $player, string $commandLabel, array $args)
    {
        $config = new Config(Main::getInstance()->getDataFolder() . "config.yml", Config::YAML);
        if ($player instanceof Player){
            $monde = Server::getInstance()->getLevelByName($config->get("world"));
            $player->teleport(new Position($config->get("x"), $config->get("y"), $config->get("z"), $monde));
            $player->getArmorInventory()->clearAll();
            $player->removeAllEffects();
            if ($player->isImmobile()){
                $player->setImmobile(false);
            }
            foreach ($player->getInventory()->getContents() as $itemclear) {
                $notClear = ["438:16", "438:29", "438:33", "466:0", "378:0"];
                if (!in_array($itemclear->getId() . ":" . $itemclear->getDamage(), $notClear)){
                    $player->getInventory()->removeItem($itemclear);
                }
            }
            $player->sendMessage(Main::$prefix . "§fVous avez été téléporté au hub !");
            $player->getInventory()->setItem(8, Item::get(Item::DRAGON_BREATH, 0, 1)->setCustomName("§r§aParamètres"));
        }
    }
}