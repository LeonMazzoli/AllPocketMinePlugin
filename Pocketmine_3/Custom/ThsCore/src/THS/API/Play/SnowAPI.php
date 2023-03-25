<?php

namespace THS\API\Play;

use pocketmine\item\Item;
use pocketmine\level\Position;
use pocketmine\Player;
use pocketmine\Server;
use THS\API\Game;

class SnowAPI{
    public static function start(Player $player){
        $kitpvp = Server::getInstance()->getLevelByName("Arc");
        switch (mt_rand(1, 11)){
            case 1:
                $player->teleport(new Position(5, 67, -83, $kitpvp));
                break;
            case 2:
                $player->teleport(new Position(-52, 54, -63, $kitpvp));
                break;
            case 3:
                $player->teleport(new Position(0, 51, 0, $kitpvp));
                break;
            case 4:
                $player->teleport(new Position(-38, 67, 89, $kitpvp));
                break;
            case 5:
                $player->teleport(new Position(-15, 59, 103, $kitpvp));
                break;
            case 6:
                $player->teleport(new Position(29, 66, 90, $kitpvp));
                break;
            case 7:
                $player->teleport(new Position(83, 64, 65, $kitpvp));
                break;
            case 8:
                $player->teleport(new Position(-70, 67, -10, $kitpvp));
                break;
            case 9:
                $player->teleport(new Position(-89, 58, 57, $kitpvp));
                break;
            case 10:
                $player->teleport(new Position(71, 75, 5, $kitpvp));
                break;
            case 11:
                $player->teleport(new Position(68, 84, -60, $kitpvp));
                break;
        }
        self::kit($player);
    }

    public static function kit(Player $player){
        Game::removeItem($player);
        $player->getInventory()->addItem(Item::get(Item::SNOWBALL, 0, 99999));
    }
}