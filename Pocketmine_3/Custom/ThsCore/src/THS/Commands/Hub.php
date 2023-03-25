<?php

namespace THS\Commands;

use pocketmine\command\CommandSender;
use pocketmine\command\PluginCommand;
use pocketmine\level\Position;
use pocketmine\Player;
use pocketmine\Server;
use pocketmine\utils\Config;
use THS\API\ItemAPI;
use THS\Main;

class Hub extends PluginCommand{
    public function __construct(Main $main)
    {
        parent::__construct("hub", $main);
    }

    public function execute(CommandSender $player, string $commandLabel, array $args)
    {
        $config = new Config(Main::getInstance()->getDataFolder() . "config.yml", Config::YAML);
        if ($player instanceof Player){
            $x = $config->get("Hub")[0];
            $y = $config->get("Hub")[1];
            $z = $config->get("Hub")[2];
            $m = $config->get("Hub")[3];
            $pos = new Position($x, $y, $z, Server::getInstance()->getLevelByName($m));
            $player->teleport($pos);
            $player->getArmorInventory()->clearAll();
            if ($player->isImmobile()){
                $player->setImmobile(false);
            }

            ItemAPI::item($player);
            $player->setNameTag($player->getName());
            $player->setNameTagAlwaysVisible(true);
            $player->setGamemode(0);
        }
    }
}