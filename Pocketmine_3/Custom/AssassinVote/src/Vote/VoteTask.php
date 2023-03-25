<?php

namespace Vote;

use pocketmine\command\ConsoleCommandSender;
use pocketmine\item\Item;
use pocketmine\scheduler\AsyncTask;
use pocketmine\Server;
use pocketmine\utils\Config;
use pocketmine\utils\Internet;

class VoteTask extends AsyncTask
{
    private $target;
    public function __construct(string $username)
    {
        $this->target = $username;
    }

    public function onRun(): void{
        $result = Internet::getURL("https://minecraftpocket-servers.com/api/?object=votes&element=claim&key=9mFmICCtBkg2Yl0qANUJwqx6vHnNVpsis0&username=" . str_replace(" ", "+", $this->target));
        if($result === "1") Internet::getURL("https://minecraftpocket-servers.com/api/?action=post&object=votes&element=claim&key=9mFmICCtBkg2Yl0qANUJwqx6vHnNVpsis0&username=" . str_replace(" ", "+", $this->target));
        $this->setResult($result);
    }

    public function onCompletion(Server $server): void{
        $result = $this->getResult();
        $player = $server->getPlayer($this->target);

        if($player === null) return;

        switch($result){
            case "0":
                $player->sendMessage("Vous n'avez pas encore voté pour aujourd'hui !");
                return;
            case "1":
                $config = new Config(VoteMain::getInstance()->getDataFolder() . "config.yml", Config::YAML);
                if ($config->get("vote") == 19){
                    $config->set("vote", 0);
                    $config->save();
                    foreach (Server::getInstance()->getOnlinePlayers() as $sender){
                        Server::getInstance()->getCommandMap()->dispatch(new ConsoleCommandSender(), "givekey {$sender->getName()} master 1");
                    }
                    Server::getInstance()->broadcastMessage("Vous venez de recevoir une clef master parce que le serveur a ateint les 20 votes !!");
                }else{
                    $config->set("vote", $config->get("vote") + 1);
                    $config->save();
                }
                Server::getInstance()->broadcastMessage($player->getName() . " §7vient de voter sur le serveur !");
                Server::getInstance()->getCommandMap()->dispatch(new ConsoleCommandSender(), "givekey {$player->getName()} vote 1");
                $player->SendMessage("Vous venez de voter et vous avez reçu une clef de vote !");
                return;
            default:
                $player->sendMessage("Vous avez déja voté aujourd'hui !");
                return;
        }
    }
}