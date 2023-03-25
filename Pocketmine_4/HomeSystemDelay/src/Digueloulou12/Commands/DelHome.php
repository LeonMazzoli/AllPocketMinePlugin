<?php

namespace Digueloulou12\Commands;

use pocketmine\command\CommandSender;
use Digueloulou12\HomeSystemDelay;
use pocketmine\command\Command;
use Digueloulou12\API\HomeAPI;
use pocketmine\player\Player;

class DelHome extends Command
{
    public function __construct()
    {
        $command = explode(":", HomeSystemDelay::getConfigValue("delhome_cmd"));
        parent::__construct($command[0]);
        if (isset($command[1])) $this->setDescription($command[1]);
        $this->setAliases(HomeSystemDelay::getConfigValue("delhome_aliases"));
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args)
    {
        if ($sender instanceof Player) {
            if (isset($args[0])) {
                if (HomeAPI::existHome($sender, $args[0])) {
                    HomeAPI::delHome($sender, $args[0]);
                    $sender->sendMessage(HomeSystemDelay::getConfigReplace("delhome_msg_good"));
                } else $sender->sendMessage(HomeSystemDelay::getConfigReplace("delhome_msg_no_exist_home"));
            } else $sender->sendMessage(HomeSystemDelay::getConfigReplace("delhome_msg_no_home"));
        }
    }
}