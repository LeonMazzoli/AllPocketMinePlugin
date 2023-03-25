<?php

namespace Vote;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\Player;
use pocketmine\plugin\PluginBase;
use pocketmine\Server;
use pocketmine\utils\Config;

class VoteMain extends PluginBase{
    private static $main;
    public function onEnable()
    {
        $this->getLogger()->info("Vote on by Digueloulou12");
        $this->saveDefaultConfig();
        self::$main = $this;
    }

    public function onCommand(CommandSender $player, Command $command, string $label, array $args): bool
    {
        switch ($command->getName()){
            case "vote":
                if ($player instanceof Player){
                    Server::getInstance()->getAsyncPool()->submitTask(new VoteTask($player->getName()));
                }else{
                    $player->sendMessage("§f» §7La commande doit être executer en jeu !");
                }
                break;
        }
        return true;
    }

    public static function getInstance(): VoteMain {
        return self::$main;
    }

    public function getVote(){
        $config = $this->getConfig();
        return $config->get("vote");
    }
}