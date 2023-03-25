<?php

namespace Assassin\Commands;

use Assassin\Main;
use pocketmine\command\CommandSender;
use pocketmine\command\PluginCommand;
use pocketmine\Player;
use pocketmine\utils\Config;

class Edit extends PluginCommand{
    private $main;
    public static $edit = [];
    public function __construct(Main $main)
    {
        parent::__construct("edit", $main);
        $this->setDescription("Permet de modifier le statu du monde");
        $this->setPermission("edit.use");
        $this->main = $main;
    }

    public function execute(CommandSender $player, string $commandLabel, array $args)
    {
        $config = new Config(Main::getInstance()->getDataFolder() . "world.yml", Config::YAML);
        if (!($player instanceof Player)) return $player->sendMessage(Main::$prefix."La commande doit être executer en jeu !");
        if (!$player->hasPermission("edit.use")) return $player->sendMessage(Main::$prefix."Vous n'avez pas la permission d'utiliser cette commande !");


        if (isset($args[0]) and $args[0] === "world"){
            $world = $player->getLevel()->getName();
            if ($config->get($world) === "oui"){
                $config->set($world, "non");
                $on = "non";
            }elseif ($config->get($world) === "non"){
                $config->set($world, "oui");
                $on = "oui";
            }else{
                $config->set($world, "non");
                $on = "non";
            }
            $config->save();
            $player->sendMessage(Main::$prefix."Vous venez de définir le monde§a $world §fsur§a $on §f!");
        }else{
            if (isset(self::$edit[$player->getName()])){
                unset(self::$edit[$player->getName()]);
                $player->sendMessage(Main::$prefix."Vous venez de quitter le mode edit !");
            }else{
                self::$edit[$player->getName()] = $player;
                $player->sendMessage(Main::$prefix."Vous venez de passé en mode edit !");
            }
        }
        return true;
    }
}