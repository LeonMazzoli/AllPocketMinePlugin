<?php

namespace Assassin\Commands;

use Assassin\Main;
use Assassin\Tasks\RedemTask;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;

class Redem extends Command{
    public function __construct(Main $main)
    {
        parent::__construct("redem", "Regarde le temps avant le redem !");
    }

    public function execute(CommandSender $player, string $commandLabel, array $args)
    {
        $time = RedemTask::$time;
        $player->sendMessage(Main::$prefix . "§fLe prochain redem se fait dans§a $time §fseconde(s) !");
    }
}