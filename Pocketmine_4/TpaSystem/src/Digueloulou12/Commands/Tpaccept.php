<?php

namespace Digueloulou12\Commands;

use pocketmine\entity\effect\EffectInstance;
use pocketmine\entity\effect\VanillaEffects;
use Digueloulou12\Task\TpaTask;
use pocketmine\command\CommandSender;
use pocketmine\command\Command;
use pocketmine\player\Player;
use Digueloulou12\TpaSystem;
use pocketmine\Server;

class Tpaccept extends Command
{
    public function __construct()
    {
        $command = explode(":", TpaSystem::getConfigValue("tpaccept_cmd"));
        parent::__construct($command[0]);
        if (isset($command[1])) $this->setDescription($command[1]);
        $this->setAliases(TpaSystem::getConfigValue("tpaccept_aliases"));
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args)
    {
        if ($sender instanceof Player) {
            $command = explode(":", TpaSystem::getConfigValue("tpaccept_cmd"));
            if ((isset($command[2])) and (TpaSystem::hasPermissionPlayer($sender, $command[2]))) return;
            if (!empty(Tpa::$invitation[$sender->getName()])) {
                if (Tpa::$invitation[$sender->getName()]["time"] > time()) {
                    $player = Server::getInstance()->getPlayerByPrefix(Tpa::$invitation[$sender->getName()]["player"]);
                    if ($player instanceof Player) {
                        if (Tpa::$invitation[$sender->getName()]["here"]) {
                            $sender->getEffects()->add(new EffectInstance(VanillaEffects::BLINDNESS(), 20 * (TpaSystem::getConfigValue("tpa_delay") + 2), 10));
                            new TpaTask($sender, $player->getPosition());
                        } else {
                            $player->getEffects()->add(new EffectInstance(VanillaEffects::BLINDNESS(), 20 * (TpaSystem::getConfigValue("tpa_delay") + 2), 10));
                            new TpaTask($player, $sender->getPosition());
                        }
                        unset(Tpa::$invitation[$sender->getName()]);
                    } else $sender->sendMessage(TpaSystem::getConfigReplace("tpaccept_no_online"));
                } else $sender->sendMessage(TpaSystem::getConfigReplace("tpaccept_expired"));
            } else $sender->sendMessage(TpaSystem::getConfigReplace("tpaccept_no_invitation"));
        }
    }
}