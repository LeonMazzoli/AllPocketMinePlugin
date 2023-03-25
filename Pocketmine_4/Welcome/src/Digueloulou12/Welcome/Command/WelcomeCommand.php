<?php

namespace Digueloulou12\Welcome\Command;

use Digueloulou12\Welcome\Utils\Utils;
use Digueloulou12\Welcome\Welcome;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;

class WelcomeCommand extends Command
{
    public function __construct(string $name, string $description = "", array $aliases = [])
    {
        parent::__construct($name, $description, null, $aliases);
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args)
    {
        if ($sender instanceof Player) {
            if (!is_null(Welcome::getInstance()->getNewPlayer())) {
                if (Welcome::getInstance()->isValidTime()) {
                    if ($sender->getName() !== Welcome::getInstance()->getNewPlayer()) {
                        if (!Welcome::getInstance()->alreadyWelcome($sender->getName())) {
                            Welcome::getInstance()->addPlayerWelcome($sender->getName());
                            $sender->getServer()->broadcastMessage(Utils::getConfigReplace("welcome_server", ["{player}", "{new_player}"], [$sender->getName(), Welcome::getInstance()->getNewPlayer()]));
                            $sender->sendMessage(Utils::getConfigReplace("welcome_good", "{player}", Welcome::getInstance()->getNewPlayer()));
                        } else $sender->sendMessage(Utils::getConfigReplace("already_welcome"));
                    } else $sender->sendMessage(Utils::getConfigReplace("no_welcome_you"));
                } else $sender->sendMessage(Utils::getConfigReplace("no_time"));
            } else $sender->sendMessage(Utils::getConfigReplace("no_new_player"));
        } else $sender->sendMessage(Utils::getConfigReplace("no_player"));
    }
}