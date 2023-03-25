<?php

namespace Command\Commands;

use Command\Evonia;
use pocketmine\block\MonsterSpawner;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\math\Vector3;
use pocketmine\Player;
use pocketmine\utils\Config;

class Rtp extends Command{
    private $main;
    public function __construct(Evonia $main)
    {
        $config = new Config(Evonia::getInstance()->getDataFolder() . "config.yml", Config::YAML);
        parent::__construct("rtp", $config->get("desc.rtp"), "/rtp");
    }

    public function execute(CommandSender $player, string $commandLabel, array $args)
    {
        $config = new Config(Evonia::getInstance()->getDataFolder() . "config.yml", Config::YAML);
        if ($player instanceof Player){
            $pos = $player->getLevel()->getSafeSpawn(new Vector3(rand('-'.$config->get("wild-MaxX"), $config->get("wild-MaxX")),rand(70,100),rand('-'.$config->get("wild-MaxY"), $config->get("wild-MaxY"))));
            $pos->getLevel()->loadChunk($pos->getX(),$pos->getZ());
            $pos->getLevel()->getChunk($pos->getX(),$pos->getZ(),true);
            $pos = $pos->getLevel()->getSafeSpawn(new Vector3($pos->getX(), rand(4,100), $pos->getZ()));
            $player->teleport($pos->getLevel()->getSafeSpawn(new Vector3($pos->getX(), rand(4,100),$pos->getZ())));
            $player->sendMessage($config->get("message.rtp"));
        }else{
            $player->sendMessage($config->get("console.rtp"));
        }
    }
}