<?php

namespace Digueloulou12\Commands;

use Digueloulou12\Forms\WarpForms;
use Digueloulou12\Task\TeleportationTask;
use pocketmine\command\CommandSender;
use pocketmine\command\Command;
use Digueloulou12\API\WarpAPI;
use pocketmine\entity\effect\EffectInstance;
use pocketmine\entity\effect\VanillaEffects;
use pocketmine\player\Player;
use Digueloulou12\WarpDelay;

class Warp extends Command
{
    public function __construct()
    {
        $command = explode(":", WarpDelay::getConfigValue("warp_cmd"));
        parent::__construct($command[0]);
        if (isset($command[1])) $this->setDescription($command[1]);
        $this->setAliases(WarpDelay::getConfigValue("warp_aliases"));
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args)
    {
        if ($sender instanceof Player) {
            $command = explode(":", WarpDelay::getConfigValue("warp_cmd"));
            if ((isset($command[2])) and (WarpDelay::hasPermissionPlayer($sender, $command[2]))) return;
            if ((isset($args[0])) and (WarpAPI::existWarp($args[0]))) {
                if (($sender->hasPermission(WarpDelay::getConfigValue("warp_perm_tp"))) or (WarpDelay::getConfigValue("delay") === null)) {
                    $sender->teleport(WarpAPI::getWarp($args[0]));
                    $sender->sendMessage(WarpDelay::getConfigReplace("warp_msg_teleport"));
                } else {
                    $sender->getEffects()->add(new EffectInstance(VanillaEffects::BLINDNESS(), 20 * (WarpDelay::getConfigValue("delay") + 2), 10));
                    new TeleportationTask($sender, $args[0]);
                }
            } else {
                if (WarpDelay::getConfigValue("form")) {
                    $sender->sendForm(WarpForms::warpForm());
                    return;
                }

                $warps = implode(", ", WarpAPI::getAllWarps());
                $sender->sendMessage(WarpDelay::getConfigReplace("warp_msg_list", ["{warp}"], [$warps]));
            }
        }
    }
}