<?php

namespace Digueloulou12\Commands;

use pocketmine\command\CommandSender;
use pocketmine\lang\Translatable;
use pocketmine\command\Command;
use pocketmine\player\Player;
use Digueloulou12\Axel;

class Heal extends Command
{
    public function __construct(string $name, Translatable|string $description = "", Translatable|string|null $usageMessage = null, array $aliases = [])
    {
        parent::__construct($name, $description, $usageMessage, $aliases);
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args)
    {
        if ($sender instanceof Player) {
            if ($sender->hasPermission("heal.use")) {
                $sender->setHealth($sender->getMaxHealth());
                $sender->sendMessage(Axel::getInstance()->getConfigValue("heal_msg"));
            } else $sender->sendMessage(Axel::getInstance()->getConfigValue("no_perm"));
        }
    }
}