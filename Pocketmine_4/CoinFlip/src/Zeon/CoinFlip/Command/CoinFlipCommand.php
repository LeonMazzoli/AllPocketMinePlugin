<?php

namespace Zeon\CoinFlip\Command;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;
use Zeon\CoinFlip\Forms\CoinFlipForms;

class CoinFlipCommand extends Command
{
    public function __construct()
    {
        parent::__construct("coinflip");
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args)
    {
        if ($sender instanceof Player) {
            CoinFlipForms::coinForm($sender);
        }
    }
}