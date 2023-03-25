<?php

namespace Digueloulou12;

use pocketmine\command\CommandSender;
use pocketmine\entity\EffectInstance;
use pocketmine\command\Command;
use pocketmine\entity\Effect;
use pocketmine\Player;

class SpawnCommand extends Command
{
    public function __construct()
    {
        $command = explode(":", SpawnDelay::getConfigValue("command"));
        parent::__construct($command[0]);
        if (isset($command[1])) $this->setDescription($command[1]);
        $this->setAliases(SpawnDelay::getConfigValue("command_aliases"));
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args)
    {
        if ($sender instanceof Player) {
            $command = explode(":", "command");
            if ((isset($command[2])) and !($sender->hasPermission($command[2]))) return;

            if ($sender->hasPermission("spawn.use")) {
                $sender->teleport($sender->getLevel()->getSafeSpawn());
                $sender->sendTip(SpawnDelay::getConfigReplace("teleportation"));
            } else {
                $sender->addEffect(new EffectInstance(Effect::getEffect(Effect::BLINDNESS), 20 * (SpawnDelay::getConfigValue("delay") + 2), 10));
                new SpawnTask($sender);
            }
        }
    }
}