<?php

namespace Assassin\Events;

use Assassin\Main;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\Player;
use pocketmine\Server;
use pocketmine\utils\Config;

class Param implements Listener{
    public function onInter(PlayerInteractEvent $event){
        $player = $event->getPlayer();

        if ($event->getItem()->getId() === 437){
            if ($player->getLevel()->getName() === "Lobby"){
                $this->paramUI($player);
            }
        }
    }

    public function paramUI(Player $player){
        $config = new Config(Main::getInstance()->getDataFolder() . "param.yml", Config::YAML);
        $api = Server::getInstance()->getPluginManager()->getPlugin("FormAPI");
        $form = $api->createSimpleForm(function (Player $player, int $data = null) use ($config){
            $result = $data;
            if ($result === null){
                return false;
            }
            switch ($result){
                case 1:
                    Server::getInstance()->getCommandMap()->dispatch($player, "cps");
                    break;
                case 0:
                    if ($config->getNested("to." . $player->getName()) === 1){
                        Server::getInstance()->getCommandMap()->dispatch($player, "togglesprint off");
                        $config->setNested("to." . $player->getName(), 0);
                        $config->save();
                    }else{
                        Server::getInstance()->getCommandMap()->dispatch($player, "togglesprint on");
                        $config->setNested("to." . $player->getName(), 1);
                        $config->save();
                    }
                    break;
            }
        });
        $form->setTitle("Réglages");
        $form->setContent("Choisie ce que tu veux modifier:");
        $form->addButton("§3ToggleSprint");
        $form->addButton("§aCPS");
        $form->sendToPlayer($player);
        return $form;
    }
}