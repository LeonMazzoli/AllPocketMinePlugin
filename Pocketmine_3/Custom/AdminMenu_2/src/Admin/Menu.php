<?php

namespace Admin;

use Admin\Command\AdminMenu;
use Admin\Event\AdminEvent;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\Config;

class Menu extends PluginBase{
    private static $menu;
    public function onEnable()
    {
        $this->saveDefaultConfig();

        self::$menu = $this;

        $command = explode(":", $this->getConfigValue("command"));
        $this->getServer()->getCommandMap()->register($command[0], new AdminMenu($this));
        $this->getServer()->getPluginManager()->registerEvents(new AdminEvent(), $this);
    }

    public static function getInstance(): Menu {
        return self::$menu;
    }

    public static function getConfigValue(string $value){
        $config = new Config(Menu::getInstance()->getDataFolder()."config.yml",Config::YAML);
        return $config->get($value);
    }
}