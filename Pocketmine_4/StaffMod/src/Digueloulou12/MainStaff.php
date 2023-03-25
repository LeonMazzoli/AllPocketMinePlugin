<?php

namespace Digueloulou12;

use Digueloulou12\Listener\EventsListener;
use Digueloulou12\Command\StaffCommand;
use pocketmine\plugin\PluginBase;
use Digueloulou12\API\StaffAPI;
use pocketmine\Server;

class MainStaff extends PluginBase
{
    private static MainStaff $main;

    public function onEnable(): void
    {
        self::$main = $this;
        $this->saveDefaultConfig();

        $command = explode(":", StaffAPI::getConfigValue("command"));
        Server::getInstance()->getCommandMap()->register($command[0], new StaffCommand($this));
        Server::getInstance()->getPluginManager()->registerEvents(new EventsListener(), $this);
    }

    public static function getInstance(): MainStaff
    {
        return self::$main;
    }
}