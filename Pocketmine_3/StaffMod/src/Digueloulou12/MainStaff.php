<?php

namespace Digueloulou12;

use Digueloulou12\API\StaffAPI;
use Digueloulou12\Command\StaffCommand;
use Digueloulou12\Listener\EventsListener;
use pocketmine\plugin\PluginBase;
use pocketmine\Server;

class MainStaff extends PluginBase{
    private static $main;
    public function onEnable()
    {
        self::$main = $this;
        $this->saveResource("config.yml");

        $command = explode(":", StaffAPI::getConfigValue("command"));
        Server::getInstance()->getCommandMap()->register($command[0], new StaffCommand($this));
        Server::getInstance()->getPluginManager()->registerEvents(new EventsListener(), $this);
    }

    public static function getInstance(): MainStaff{
        return self::$main;
    }
}