<?php

namespace Digueloulou12\Commands;

use pocketmine\entity\effect\VanillaEffects;
use pocketmine\entity\effect\EffectInstance;
use Digueloulou12\Task\TeleportationTask;
use pocketmine\command\CommandSender;
use Digueloulou12\HomeSystemDelay;
use pocketmine\command\Command;
use Digueloulou12\API\HomeAPI;
use pocketmine\player\Player;

class Home extends Command
{
    public function __construct()
    {
        $command = explode(":", HomeSystemDelay::getConfigValue("home_cmd"));
        parent::__construct($command[0]);
        if (isset($command[1])) $this->setDescription($command[1]);
        $this->setAliases(HomeSystemDelay::getConfigValue("home_aliases"));
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args)
    {
        if ($sender instanceof Player) {
            if ((isset($args[0])) and (HomeAPI::existHome($sender, $args[0]))) {
                if ($sender->hasPermission(HomeSystemDelay::getConfigValue("home_teleportation_perm"))) {
                    $sender->teleport(HomeAPI::getHome($sender, $args[0]));
                    $sender->sendMessage(HomeSystemDelay::getConfigReplace("home_msg_teleport"));
                } else {
                    $sender->getEffects()->add(new EffectInstance(VanillaEffects::BLINDNESS(), 20 * (HomeSystemDelay::getConfigValue("home_time") + 2), 10));
                    new TeleportationTask($sender, HomeAPI::getHome($sender, $args[0]), HomeSystemDelay::getConfigReplace("home_msg_teleport"));
                }
            } else {
                $homes = implode(", ", HomeAPI::getAllHomes($sender));
                $sender->sendMessage(HomeSystemDelay::getConfigReplace("home_msg_list", ["{home}"], [$homes]));
            }
        }
    }
}