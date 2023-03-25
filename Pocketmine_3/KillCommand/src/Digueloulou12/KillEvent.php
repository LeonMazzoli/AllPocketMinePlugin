<?php

namespace Digueloulou12;

use pocketmine\command\ConsoleCommandSender;
use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerDeathEvent;
use pocketmine\Player;
use pocketmine\Server;
use pocketmine\utils\Config;

class KillEvent implements Listener
{
    public function onDeath(PlayerDeathEvent $event)
    {
        $player = $event->getEntity();
        $cause = $player->getLastDamageCause();

        if ($cause instanceof EntityDamageByEntityEvent) {
            $sender = $cause->getDamager();
            if ($sender instanceof Player) {
                $config = new Config(MainKill::getInstance()->getDataFolder() . "config.yml", Config::YAML);

                foreach ($config->get("commands") as $command) {
                    $commandd = explode(":", $command);
                    if ($commandd[2] === 0) {
                        switch ($commandd[0]) {
                            case "victim":
                                Server::getInstance()->getCommandMap()->dispatch($player, str_replace([strtolower("{killer}"), strtolower("{victim}")], [$sender->getName(), $player->getName()], $commandd[1]));
                                break;
                            case "killer":
                                Server::getInstance()->getCommandMap()->dispatch($sender, str_replace([strtolower("{killer}"), strtolower("{victim}")], [$sender->getName(), $player->getName()], $commandd[1]));
                                break;
                            default:
                                Server::getInstance()->getCommandMap()->dispatch(new ConsoleCommandSender(), str_replace([strtolower("{killer}"), strtolower("{victim}")], [$sender->getName(), $player->getName()], $commandd[1]));
                                break;
                        }
                    } else {
                        switch (mt_rand(1, $commandd[2])){
                            case 1:
                                switch ($commandd[0]) {
                                    case "victim":
                                        Server::getInstance()->getCommandMap()->dispatch($player, str_replace([strtolower("{killer}"), strtolower("{victim}")], [$sender->getName(), $player->getName()], $commandd[1]));
                                        break;
                                    case "killer":
                                        Server::getInstance()->getCommandMap()->dispatch($sender, str_replace([strtolower("{killer}"), strtolower("{victim}")], [$sender->getName(), $player->getName()], $commandd[1]));
                                        break;
                                    default:
                                        Server::getInstance()->getCommandMap()->dispatch(new ConsoleCommandSender(), str_replace([strtolower("{killer}"), strtolower("{victim}")], [$sender->getName(), $player->getName()], $commandd[1]));
                                        break;
                                }
                            break;
                        }
                    }
                }
            }
        }
    }
}