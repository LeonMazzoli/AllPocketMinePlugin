<?php

namespace THS\Events;

use pocketmine\event\Listener;
use pocketmine\event\player\PlayerPreLoginEvent;
use pocketmine\utils\Config;
use THS\API\LanguageAPI;
use THS\Main;

class LoginEvent implements Listener{
    public function onLogin(PlayerPreLoginEvent $event){
        $tban = new Config(Main::getInstance()->getDataFolder()."tban.json", Config::JSON);
        $banperm = new Config(Main::getInstance()->getDataFolder()."banperm.json", Config::JSON);
        $player = $event->getPlayer();

        if ($banperm->exists($player->getName())){
            if (LanguageAPI::getLanguage($player) === "fr"){
                $player->close("Vous etes banni pour {$banperm->get($player->getName())} !", "Vous etes banni pour {$banperm->get($player->getName())} !");
            }else $player->close("You are banned for {$banperm->get($player->getName())} !", "You are banned for {$banperm->get($player->getName())} !");
            $event->setCancelled(true);
            return;
        }

        if ($tban->exists($player->getName())){
            if ($tban->get($player->getName())["temps"] > time()){
                $time = $tban->get($player->getName())["temps"] - time();
                $day = floor($time / 86400);
                $hour2 = $time % 86400;
                $hour = floor($hour2 / 3600);
                $minutes2 = $time % 3600;
                $minute = floor($minutes2 / 60);
                if (LanguageAPI::getLanguage($player) === "fr"){
                    $player->close("Vous etes banni pendant encore§a $minute §fminute(s),§a $hour §fheure(s) et§a $day §fjour(s) pour §a{$tban->get($player->getName())["raison"]} §f!",
                    "Vous etes banni pendant encore§a $minute §fminute(s),§a $hour §fheure(s) et§a $day §fjour(s) pour §a{$tban->get($player->getName())["raison"]} §f!");
                }else $player->close("You are banned for yet $minute §fminute(s),§a $hour §fhour(s) et§a $day §fday(s) for §a{$tban->get($player->getName())["raison"]} §f!",
                    "You are banned for another $minute §fminute(s),§a $hour §fhour(s) et§a $day §fday(s) for §a{$tban->get($player->getName())["raison"]} §f!");
                $event->setCancelled(true);
            }
        }
    }
}