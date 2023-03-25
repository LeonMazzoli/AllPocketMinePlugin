<?php

namespace Digueloulou12\Advantages\Command;

use Digueloulou12\Advantages\Inventory\AdvantagesInventory;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;

class AdvantagesCommand extends Command
{
    public function __construct(string $name, string $description = "", array $aliases = [])
    {
        parent::__construct($name, $description, null, $aliases);
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args)
    {
        if ($sender instanceof Player) AdvantagesInventory::sendAdvantagesInventory($sender);
    }
}