<?php

namespace THS;

use muqsit\invmenu\InvMenuHandler;
use pocketmine\plugin\PluginBase;
use pocketmine\Server;
use THS\API\DiscordAPI;
use THS\API\LoadAPI;
use THS\Tasks\ClearlaggTask;

class Main extends PluginBase
{
    public static $prefix = "§0[§aTHS§0] §f";
    public static $ig = "§0[§aTHS§0] §fLa commande doit être executer en jeu !";
    public static $noperm = "§0[§aTHS§0] §fVous n'avez pas la permission d'utiliser cette commande !";
    private static $main;

    public function onEnable()
    {
        // Message Load
        $this->getLogger()->info("§b---------------------------");
        $this->getLogger()->info("§bThsCore on by Digueloulou12");
        $this->getLogger()->info("§b---------------------------");

        DiscordAPI::sendMessage("**Serveur __on__**");
        LoadAPI::unloadCommands();
        LoadAPI::loadCommands($this);
        LoadAPI::loadEvents($this);
        LoadAPI::setHandler();

        if (!InvMenuHandler::isRegistered()){
            InvMenuHandler::register($this);
        }

        $this->getScheduler()->scheduleRepeatingTask(new ClearlaggTask(), 20 * 5);

        foreach (array_diff(scandir($this->getServer()->getDataPath() . "worlds"), ["..", "."]) as $levelName) {
            if ($this->getServer()->loadLevel($levelName)) {
                $this->getLogger()->debug("Successfully loaded §6{$levelName}");
            }
        }

        self::$main = $this;
    }

    public function onDisable()
    {
        // Message Unload
        $this->getLogger()->info("§b----------------------------");
        $this->getLogger()->info("§bThsCore off by Digueloulou12");
        $this->getLogger()->info("§b----------------------------");

        // Discord
        DiscordAPI::sendMessage("**Serveur __off__**");

        // Transfer
        foreach (Server::getInstance()->getOnlinePlayers() as $sender) {
            $sender->getArmorInventory()->clearAll();

            foreach ($sender->getInventory()->getContents() as $itemclear) {
                $notClear = ["438:16", "438:29", "438:33", "466:0", "378:0"];
                if (!in_array($itemclear->getId() . ":" . $itemclear->getDamage(), $notClear)){
                    $sender->getInventory()->removeItem($itemclear);
                }
            }
        }
    }

    public static function getInstance(): Main
    {
        return self::$main;
    }
}