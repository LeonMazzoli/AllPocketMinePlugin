<?php

namespace Digueloulou12\Commands;

use pocketmine\command\CommandSender;
use pocketmine\lang\Translatable;
use pocketmine\command\Command;
use pocketmine\player\Player;
use Digueloulou12\Axel;

class Feed extends Command
{
    public function __construct(string $name, Translatable|string $description = "", Translatable|string|null $usageMessage = null, array $aliases = [])
    {
        parent::__construct($name, $description, $usageMessage, $aliases);
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args)
    {
        if ($sender instanceof Player) {
            if ($sender->hasPermission("feed.use")) {
                $sender->getHungerManager()->setFood($sender->getHungerManager()->getMaxFood());
                $sender->getHungerManager()->setSaturation(20);
                $sender->sendMessage(Axel::getInstance()->getConfigValue("feed_msg"));
            } else $sender->sendMessage(Axel::getInstance()->getConfigValue("no_perm"));
        }
    }
}