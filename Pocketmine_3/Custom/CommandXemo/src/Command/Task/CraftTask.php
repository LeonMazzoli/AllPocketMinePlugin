<?php

namespace Command\Task;

use Command\Xemo;
use pocketmine\level\Position;
use pocketmine\network\mcpe\protocol\PlaySoundPacket;
use pocketmine\Player;
use pocketmine\scheduler\Task;
use pocketmine\Server;
use pocketmine\utils\Config;

class CraftTask extends Task{
    private $player;
    private $target;
    private $Px;
    private $Py;
    private $Pz;
    private $time = 5;

    public function __construct(Player $player, Player $target, $x, $y, $z) {
        $this->player = $player;
        $this->target = $target;
        $this->Px = $x;
        $this->Py = $y;
        $this->Pz = $z;
    }

    public function onRun(int $currentTick) {
        $config = new Config(Xemo::getInstance()->getDataFolder() . "config.yml", Config::YAML);
        $player = $this->player;

        $Px = round($player->getX());
        $Py = round($player->getY());
        $Pz = round($player->getZ());
        $x = round($this->Px);
        $y = round($this->Py);
        $z = round($this->Pz);

        if (($Px != $x) or ($Py != $y) or ($Pz != $z)) {
            $player->removeEffect(15);
            $son = new PlaySoundPacket();
            $son->soundName = "note.bass";
            $son->volume = 100;
            $son->pitch = 1;
            $son->x = $player->x;
            $son->y = $player->y;
            $son->z = $player->z;
            $player->sendDataPacket($son);
            $player->sendTip($config->get("bouge.craft"));
            Xemo::removeTask($this->getTaskId());
            return;
        }


        $config = new Config(Xemo::getInstance()->getDataFolder() . "config.yml", Config::YAML);
        $player = $this->player;
        if ($this->time === 0) {
            $son = new PlaySoundPacket();
            $son->soundName = "note.flute";
            $son->volume = 100;
            $son->pitch = 1;
            $son->x = $player->x;
            $son->y = $player->y;
            $son->z = $player->z;
            $player->sendDataPacket($son);
            $son = new PlaySoundPacket();
            $son->soundName = "note.bass";
            $son->volume = 100;
            $son->pitch = 1;
            $son->x = $player->x;
            $son->y = $player->y;
            $son->z = $player->z;
            $player->sendDataPacket($son);
            $player->sendMessage($config->get("craft.yes"));
            $x = $config->get("craft")[0];
            $y = $config->get("craft")[1];
            $z = $config->get("craft")[2];
            $monde = $config->get("craft")[3];
            $player->teleport(new Position($x, $y, $z, Server::getInstance()->getLevelByName($monde)));
            Xemo::getInstance()->getScheduler()->cancelTask($this->getTaskId());
        }else{
            $custommessage = $config->get("craft.tp");
            $message = str_replace("{time}", $this->time, $custommessage);
            $player->sendTip($message);
            $son = new PlaySoundPacket();
            $son->soundName = "note.harp";
            $son->volume = 100;
            $son->pitch = 1;
            $son->x = $player->x;
            $son->y = $player->y;
            $son->z = $player->z;
            $player->sendDataPacket($son);
        }
        $this->time--;
    }
}