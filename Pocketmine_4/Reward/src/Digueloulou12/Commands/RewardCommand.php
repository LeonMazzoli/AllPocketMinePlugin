<?php

namespace Digueloulou12\Commands;

use pocketmine\command\CommandSender;
use Digueloulou12\Forms\RewardForms;
use pocketmine\lang\Translatable;
use pocketmine\command\Command;
use pocketmine\player\Player;

class RewardCommand extends Command
{
    public function __construct(string $name, Translatable|string $description = "", Translatable|string|null $usageMessage = null, array $aliases = [])
    {
        parent::__construct($name, $description, $usageMessage, $aliases);
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args)
    {
        if ($sender instanceof Player) {
            $sender->sendForm(RewardForms::mainForm($sender->getName()));
        }
    }
}