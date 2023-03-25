<?php

namespace Digueloulou12\Command;

use pocketmine\command\CommandSender;
use pocketmine\item\ItemFactory;
use pocketmine\command\Command;
use pocketmine\player\Player;
use Digueloulou12\Crate;
use pocketmine\Server;

class CrateCommand extends Command
{
    public function __construct()
    {
        $command = explode(":", Crate::getConfigValue("key"));
        parent::__construct($command[0]);
        if (isset($command[1])) $this->setDescription($command[1]);
        $this->setAliases(Crate::getConfigValue("key_aliases"));
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args)
    {
        $command = explode(":", Crate::getConfigValue("key"));
        if ((isset($command[2])) and (Crate::hasPermissionPlayer($sender, $command[2]))) return;
        if (isset($args[0])) {
            $player = Server::getInstance()->getPlayerByPrefix($args[0]);
            if ($player instanceof Player) {
                if (isset($args[1])) {
                    if (Crate::getInstance()->getConfig()->getNested("crate.$args[1]") !== null) {
                        if (isset($args[2])) {
                            if (is_numeric($args[2])) {
                                $item = ItemFactory::getInstance()->get((int)Crate::getConfigValue("crate_key_id"), 0, $args[2])->setLore([$args[1]]);
                                if ($player->getInventory()->canAddItem($item)) {
                                    $player->getInventory()->addItem($item);
                                } else $player->getWorld()->dropItem($player->getPosition(), $item);
                                $sender->sendMessage(Crate::getConfigReplace("key_good", ["{count}", "{crate}", "{player}"], [$args[2], $args[1], $player->getName()]));
                            } else $sender->sendMessage(Crate::getConfigReplace("key_numeric"));
                        } else $sender->sendMessage(Crate::getConfigReplace("key_amount"));
                    } else $sender->sendMessage(Crate::getConfigReplace("key_no_exist_crate"));
                } else $sender->sendMessage(Crate::getConfigReplace("key_no_crate"));
            } else $sender->sendMessage(Crate::getConfigReplace("key_no_online_player"));
        } else $sender->sendMessage(Crate::getConfigReplace("key_no_player"));
    }
}