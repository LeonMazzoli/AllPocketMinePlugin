<?php

namespace Assassin\Events;

use Assassin\Main;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\item\Item;
use pocketmine\level\Position;
use pocketmine\Server;
use pocketmine\utils\Config;

class Join implements Listener{
    public function onJoin(PlayerJoinEvent $event){
        $config = new Config(Main::getInstance()->getDataFolder() . "config.yml", Config::YAML);
        $configp = new Config(Main::getInstance()->getDataFolder() . "param.yml", Config::YAML);
        $player = $event->getPlayer();
        $event->setJoinMessage("");

        if (!$player->hasPlayedBefore()){
            $player->setGamemode(0);
            $player->addTitle("§aAssassin");
            Server::getInstance()->broadcastMessage(Main::$prefix . str_replace("{player}", $player->getName(), $config->get("connexionp")));
            $monde = Server::getInstance()->getLevelByName($config->get("world"));
            $player->teleport(new Position($config->get("x"), $config->get("y"), $config->get("z"), $monde));
            $player->getInventory()->clearAll();
            $player->getArmorInventory()->clearAll();
            $player->removeAllEffects();
            $player->getInventory()->setItem(8, Item::get(Item::DRAGON_BREATH, 0, 1)->setCustomName("§r§aParamètres"));
            $configp->setNested("sh." . $player->getName(), 1);
            $configp->setNested("cps." . $player->getName(), 1);
            $configp->setNested("to." . $player->getName(), 0);
            $configp->save();
        }else{
            $player->addTitle("§aAssassin");
            $player->setGamemode(0);
            Server::getInstance()->broadcastMessage(Main::$prefix . str_replace("{player}", $player->getName(), $config->get("connexion")));
            $monde = Server::getInstance()->getLevelByName($config->get("world"));
            $player->teleport(new Position($config->get("x"), $config->get("y"), $config->get("z"), $monde));
            $player->getInventory()->clearAll();
            $player->getArmorInventory()->clearAll();
            $player->removeAllEffects();
            $player->getInventory()->setItem(8, Item::get(Item::DRAGON_BREATH, 0, 1)->setCustomName("§r§aParamètres"));
            if ($configp->getNested("cps." . $player->getName()) === 0){
                Server::getInstance()->getCommandMap()->dispatch($player, "cps");
            }
        }
    }
}