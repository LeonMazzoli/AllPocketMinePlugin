<?php

namespace Digueloulou12;

use pocketmine\level\Position;
use pocketmine\Player;
use pocketmine\scheduler\Task;
use pocketmine\Server;

class DuelTask extends Task
{
    private $player1;
    private $player2;
    public $timer = 11;

    public function __construct(Player $player, Player $sender)
    {
        $this->player1 = $player;
        $this->player2 = $sender;
        MainDuel::getInstance()->getScheduler()->scheduleDelayedRepeatingTask($this, 20, 20);
    }

    public function onRun(int $currentTick)
    {
        $player = $this->player1;
        $sender = $this->player2;

        if (!($player instanceof Player) or !($sender instanceof Player)){
            DuelAPI::stopGame();
            $this->getHandler()->cancel();
            return;
        }

        if (empty(DuelAPI::$players[$player->getName()])){
            $this->getHandler()->cancel();
            return;
        }

        if (empty(DuelAPI::$players[$sender->getName()])){
            $this->getHandler()->cancel();
            return;
        }

        if ($this->timer === 10){
            $pos1 = explode(":", MainDuel::$config->get("pos1"));
            $pos2 = explode(":", MainDuel::$config->get("pos2"));
            $player->teleport(new Position($pos1[0], $pos1[1], $pos1[2], Server::getInstance()->getLevelByName($pos1[3])));
            $sender->teleport(new Position($pos2[0], $pos2[1], $pos2[2], Server::getInstance()->getLevelByName($pos2[3])));

            DuelAPI::$god[$player->getName()] = $player;
            DuelAPI::$god[$sender->getName()] = $sender;

            $player->setHealth($player->getMaxHealth());
            $sender->setHealth($sender->getMaxHealth());

            DuelAPI::setKit($player);
            DuelAPI::setKit($sender);

            $player->setImmobile(true);
            $sender->setImmobile(true);
        }

        if ($this->timer === 5){
            $player->setImmobile(false);
            $sender->setImmobile(false);

            unset(DuelAPI::$god[$player->getName()]);
            unset(DuelAPI::$god[$sender->getName()]);
        }

        if ($this->timer === 0){
            $this->getHandler()->cancel();
            return;
        }

        $this->timer--;
    }
}