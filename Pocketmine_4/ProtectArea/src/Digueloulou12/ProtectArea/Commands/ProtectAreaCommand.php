<?php

namespace Digueloulou12\ProtectArea\Commands;

use Digueloulou12\ProtectArea\Forms\ProtectAreaForm;
use Digueloulou12\ProtectArea\Utils\Utils;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;

class ProtectAreaCommand extends Command
{
    public function __construct(string $name, string $description, array $aliases)
    {
        parent::__construct($name, $description, null, $aliases);
        $this->setPermission("protectarea.use");
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args)
    {
        if ($sender instanceof Player) {
            if ($sender->hasPermission($this->getPermission())) {
                $sender->sendForm(new ProtectAreaForm());
            } else $sender->sendMessage(Utils::getConfigReplace("no_permission"));
        }
    }
}