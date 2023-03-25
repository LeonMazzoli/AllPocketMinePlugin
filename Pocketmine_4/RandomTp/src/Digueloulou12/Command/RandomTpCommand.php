<?php

namespace Digueloulou12\Command;

use pocketmine\entity\effect\EffectInstance;
use pocketmine\entity\effect\VanillaEffects;
use Digueloulou12\Task\TeleportationTask;
use pocketmine\command\CommandSender;
use pocketmine\command\Command;
use pocketmine\world\Position;
use pocketmine\player\Player;
use Digueloulou12\RandomTp;

class RandomTpCommand extends Command
{
    public function __construct()
    {
        $config = RandomTp::getInstance()->getConfig();
        parent::__construct($config->get("command")[0]);
        if (isset($config->get("command")[1])) $this->setDescription($config->get("command")[1]);
        $this->setAliases($config->get("command_aliases"));
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args)
    {
        $config = RandomTp::getInstance()->getConfig();
        if ($sender instanceof Player) {
            if (isset($config->get("command")[2])) {
                if (!$sender->hasPermission($config->get("command")[2])) return;
            }
            if (in_array($sender->getWorld()->getDisplayName(), $config->get("worlds"))) {
                $pos = new Position(
                    mt_rand($config->get("min_x"), $config->get("max_x")),
                    mt_rand($config->get("min_y"), $config->get("max_y")),
                    mt_rand($config->get("min_z"), $config->get("max_z")),
                    $sender->getWorld());
                if (!$sender->hasPermission($config->get("instant_permission"))) {
                    new TeleportationTask($sender, $pos);
                    $sender->getEffects()->add(new EffectInstance(VanillaEffects::BLINDNESS(), 20 * ($config->get("delay") + 2), 10));
                } else {
                    if ($config->get("resistance_effect")) $sender->getEffects()->add(new EffectInstance(VanillaEffects::RESISTANCE(), 20 * 20, 10, false));
                    $sender->teleport($pos);
                }
            } else $sender->sendMessage($config->get("no_world"));
        } else $sender->sendMessage($config->get("no_player"));
    }
}