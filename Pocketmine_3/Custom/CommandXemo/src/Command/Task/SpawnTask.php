<?php

namespace Command\Task;

use Command\Commands\Spawn;
use Command\Xemo;
use pocketmine\entity\Effect;
use pocketmine\network\mcpe\protocol\PlaySoundPacket;
use pocketmine\Player;
use pocketmine\scheduler\Task;
use pocketmine\utils\Config;

class SpawnTask extends Task{
    private $player;
    private $Px;
    private $Py;
    private $Pz;
    private $time = 5;
    private $plugin;

    public function __construct(Player $player, Spawn $cmd, $x, $y, $z) {
        $this->plugin = $cmd->getPlugin();
        $this->player = $player;
        $this->Px = $x;
        $this->Py = $y;
        $this->Pz = $z;
    }

    public function onRun(int $currentTick) {
        $player = $this->player;

        $Px = round($player->getX());
        $Py = round($player->getY());
        $Pz = round($player->getZ());
        $x = round($this->Px);
        $y = round($this->Py);
        $z = round($this->Pz);

        if (($Px != $x) or ($Py != $y) or ($Pz != $z)) {
            $player->removeEffect(Effect::BLINDNESS);
            $son = new PlaySoundPacket();
            $son->soundName = "note.bass";
            $son->volume = 100;
            $son->pitch = 1;
            $son->x = $player->x;
            $son->y = $player->y;
            $son->z = $player->z;
            $player->sendDataPacket($son);
            $player->sendTip("§c- §7Téléportation annulée §c-");
            $this->plugin->removeTask($this->getTaskId());
            return;
        }

        $config = new Config(Xemo::getInstance()->getDataFolder() . "config.yml", Config::YAML);
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
            $player->sendMessage($config->get("spawn.yes"));
            $player->teleport($player->getLevel()->getSpawnLocation());
            Xemo::getInstance()->getScheduler()->cancelTask($this->getTaskId());
        }else{
            $custommessage = $config->get("spawn.tp");
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