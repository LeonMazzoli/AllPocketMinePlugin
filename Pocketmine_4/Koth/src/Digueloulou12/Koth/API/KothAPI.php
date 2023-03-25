<?php

namespace Digueloulou12\Koth\API;

use Digueloulou12\Koth\Command\KothCommand;
use Digueloulou12\Koth\Task\KothTask;
use Digueloulou12\Koth\Utils\Utils;
use pocketmine\console\ConsoleCommandSender;
use pocketmine\player\Player;
use pocketmine\Server;

class KothAPI
{
    public static function isInArea(Player $player): bool
    {
        $pos = Utils::getConfigValue("koth_pos");
        $pos_ = Utils::getConfigValue("koth_pos_");
        if (($player->getPosition()->x >= min($pos[0], $pos_[0])) and ($player->getPosition()->x <= max($pos[0], $pos_[0])) and
            ($player->getPosition()->y >= min($pos[1], $pos_[1])) and ($player->getPosition()->y <= max($pos[1], $pos_[1])) and
            ($player->getPosition()->z >= min($pos[2], $pos_[2])) and ($player->getPosition()->z <= max($pos[2], $pos_[2]))) {
            return true;
        }
        return false;
    }

    public static function searchPlayer(): void
    {
        $players = Server::getInstance()->getOnlinePlayers();
        shuffle($players);
        foreach ($players as $player) {
            if (KothAPI::isInArea($player)) {
                KothTask::$player = $player;
                $player->sendMessage(Utils::getConfigReplace("koth_join"));
                break;
            }
        }
    }

    public static function leaveKoth(): void
    {
        KothTask::$time = 60;
        $player = KothTask::$player;
        if ($player->isOnline()) $player->sendMessage(Utils::getConfigReplace("koth_leave"));
        KothTask::$player = null;
    }

    public static function finishKoth(): void
    {
        KothCommand::$koth = false;
        KothCommand::$kothTask->getHandler()->cancel();
        foreach (Utils::getConfigValue("koth_commands") as $cmd) {
            Server::getInstance()->getCommandMap()->dispatch(new ConsoleCommandSender(Server::getInstance(), Server::getInstance()->getLanguage()), str_replace("{player}", KothTask::$player->getName(), $cmd));
        }
        Server::getInstance()->broadcastMessage(Utils::getConfigReplace("koth_finish", "{player}", KothTask::$player->getName()));
    }
}