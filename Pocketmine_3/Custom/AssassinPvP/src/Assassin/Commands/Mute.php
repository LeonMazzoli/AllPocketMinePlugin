<?php

namespace Assassin\Commands;

use Assassin\Main;
use jojoe77777\FormAPI\CustomForm;
use jojoe77777\FormAPI\SimpleForm;
use pocketmine\command\CommandSender;
use pocketmine\command\PluginCommand;
use pocketmine\Player;
use pocketmine\Server;

class Mute extends PluginCommand{
    private $main;
    public static $mute = [];
    public function __construct(Main $main)
    {
        parent::__construct("mute", $main);
        $this->setDescription("Mute un joueur");
        $this->setPermission("mute.use");
        $this->main = $main;
    }

    public function execute(CommandSender $player, string $commandLabel, array $args)
    {
        if (!$player->hasPermission("mute.use")) return $player->sendMessage(Main::$prefix."Vous n'avez pas la permission d'utiliser cette commande !");
        if (isset($args[0]) and !isset($args[1])) return $player->sendMessage(Main::$prefix."Vous devez indiquer un temps en minute !");
        if (isset($args[0]) and Server::getInstance()->getPlayer($args[0]) === null) return $player->sendMessage(Main::$prefix."Le joueur indiqué na pas été trouvé !");
        if (isset($args[1]) and !is_numeric($args[1])) return $player->sendMessage(Main::$prefix."Vous devez indiquer un temps en chiffre !");


        if (isset($args[0])){
            $num = $args[1] * 60;
            $sender = Server::getInstance()->getPlayer($args[0]);
            if (empty(self::$mute[$sender->getName()]) or self::$mute[$sender->getName()] <= time()){
                self::$mute[$sender->getName()] = time() + $num;
                $name = $player->getName();
                $sender->sendMessage(Main::$prefix."Vous venez d'être mute pendant§a $args[1] §fminute(s) par§a $name");
                $player->sendMessage(Main::$prefix."Vous venez de mute§a " . $sender->getName() ." §fpendant§a $args[1] §fminute(s)");
            }else{
                $player->sendMessage(Main::$prefix.$sender->getName()." §fest deja mute !");
            }
        }else $this->mute($player);
        return true;
    }

    public function mute($player)
    {
        $form = new SimpleForm(function (Player $player, $data = null){
            $result = $data;
            if ($result === null) {
                return true;
            }
            $sender = Server::getInstance()->getPlayer($result);
            if ($sender instanceof Player){
                $this->mutetime($player, $sender);
            }else{
                $player->sendMessage(Main::$prefix."Le joueur n'est plus connecté !");
            }
        });
        $form->setTitle("Mute");
        $form->setContent("Choisie le joueur a mute:");
        foreach(Server::getInstance()->getOnlinePlayers() as $online){
            $form->addButton($online->getName(), -1, "", $online->getName());
        }
        $form->sendToPlayer($player);
        return $form;
    }

    public function mutetime($player, $sender)
    {
        $form = new CustomForm(function (Player $player, array $data = null) use ($sender){
            if ($data === null) {
                return true;
            }
            if ($sender instanceof Player){
                if (is_numeric($data[1])){
                    if ($data[2] !== null) {
                        $num = $data[1] * 60;
                        if (empty(self::$mute[$sender->getName()]) or self::$mute[$sender->getName()] <= time()) {
                            self::$mute[$sender->getName()] = time() + $num;
                            $name = $player->getName();
                            $sender->sendMessage(Main::$prefix . "Vous venez d'être mute pendant§a $data[1] §fminute(s) par§a $name §fpour§a $data[2]");
                            $player->sendMessage(Main::$prefix . "Vous venez de mute§a " . $sender->getName() . " §fpendant§a $data[1] §fminute(s) pour §a$data[2] §f!");
                            Server::getInstance()->broadcastMessage(Main::$prefix."Le joueur§a {$sender->getName()} §fvient de se faire mute par§a $name §fpendant§a $data[1] §fpour§a $data[2] §f!");
                        } else {
                            $player->sendMessage(Main::$prefix . $sender->getName() . " §fest deja mute !");
                        }
                    }else $player->sendMessage(Main::$prefix."Vous devez indiqué une raison !");
                }else $player->sendMessage(Main::$prefix."Vous devez indiqué un temps en chiffre !");
            }else $player->sendMessage(Main::$prefix."Le joueur indiqué n'est pas connecté !");
        });

        $form->setTitle("Mute");
        $form->addLabel("Choisie le nombre de minutes que va être mute ");
        $form->addInput("Minute(s)");
        $form->addInput("Raison");
        $form->sendToPlayer($player);
        return $form;
    }
}