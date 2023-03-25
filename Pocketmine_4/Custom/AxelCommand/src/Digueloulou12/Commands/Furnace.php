<?php

namespace Digueloulou12\Commands;

use pocketmine\command\CommandSender;
use pocketmine\lang\Translatable;
use pocketmine\item\ItemFactory;
use pocketmine\command\Command;
use pocketmine\player\Player;
use Digueloulou12\Axel;

class Furnace extends Command
{
    public function __construct(string $name, Translatable|string $description = "", Translatable|string|null $usageMessage = null, array $aliases = [])
    {
        parent::__construct($name, $description, $usageMessage, $aliases);
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args)
    {
        if ($sender instanceof Player) {
            if ($sender->hasPermission("furnace.use")) {
                foreach (Axel::getInstance()->getConfigValue("furnace_items") as $items => $result) {
                    if ($items === $sender->getInventory()->getItemInHand()->getId() . "-" . $sender->getInventory()->getItemInHand()->getMeta()) {
                        $sender->getInventory()->setItemInHand(ItemFactory::getInstance()->get(explode("-", $result)[0], explode("-", $result)[1], $sender->getInventory()->getItemInHand()->getCount()));
                    }
                }
                $sender->sendMessage(Axel::getInstance()->getConfigValue("furnace_msg"));
            } else $sender->sendMessage(Axel::getInstance()->getConfigValue("no_perm"));
        }
    }
}