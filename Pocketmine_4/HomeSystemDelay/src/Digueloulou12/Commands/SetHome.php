<?php

namespace Digueloulou12\Commands;

use pocketmine\command\CommandSender;
use Digueloulou12\HomeSystemDelay;
use pocketmine\command\Command;
use Digueloulou12\API\HomeAPI;
use pocketmine\player\Player;

class SetHome extends Command
{
    public function __construct()
    {
        $command = explode(":", HomeSystemDelay::getConfigValue("sethome_cmd"));
        parent::__construct($command[0]);
        if (isset($command[1])) $this->setDescription($command[1]);
        $this->setAliases(HomeSystemDelay::getConfigValue("sethome_aliases"));
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args)
    {
        if ($sender instanceof Player) {
            if (isset($args[0])) {
                if (!HomeAPI::existHome($sender, $args[0])) {
                    HomeAPI::setHome($sender, $sender, $args[0]);
                    $sender->sendMessage(HomeSystemDelay::getConfigReplace("sethome_msg_good"));
                } else $sender->sendMessage(HomeSystemDelay::getConfigReplace("sethome_msg_exist_home"));
            } else $sender->sendMessage(HomeSystemDelay::getConfigReplace("sethome_msg_no_home"));
        }
    }
}