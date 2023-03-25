<?php

namespace Assassin\Tasks;

use Assassin\Main;
use pocketmine\scheduler\Task;
use pocketmine\Server;

class RedemTask extends Task
{
    public $plugin;
    public static $time = 10800;

    public function __construct(Main $plugin, $time = 10800)
    {
        $this->plugin = $plugin;
        self::$time = $time;
    }

    public function onRun(int $tick)
    {
        $time = self::$time;
        if ($time == 30 or $time == 15 or $time == 10 or $time == 5) {
            Server::getInstance()->broadcastMessage(Main::$prefix . "§fLe serveur redémarre dans§a {$time} §fseconde(s) !");
        }

        if ($time == 0) {
            Server::getInstance()->forceShutdown();
        }
        self::$time = self::$time - 5;
    }
}