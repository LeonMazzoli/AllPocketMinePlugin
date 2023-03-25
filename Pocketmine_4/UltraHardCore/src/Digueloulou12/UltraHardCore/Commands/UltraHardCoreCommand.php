<?php

namespace Digueloulou12\UltraHardCore\Commands;

use Digueloulou12\UltraHardCore\UltraHardCore;
use Digueloulou12\UltraHardCore\Utils\Utils;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;

class UltraHardCoreCommand extends Command
{
    public function __construct(string $name, string $description, array $aliases)
    {
        parent::__construct($name, $description, null, $aliases);
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args)
    {
        $api = UltraHardCore::getInstance()->getAPI();
        if ($sender instanceof Player) {
            if (isset($args[0])) {
                switch ($args[0]) {
                    case "start":
                        if ($sender->hasPermission(Utils::getConfigValue("admin_permission")) or $sender->getServer()->isOp($sender->getName())) {
                            if (!$api->isGame()) {
                                $api->startGame();
                                $sender->sendMessage(Utils::getConfigReplace("start_game"));
                            } else $sender->sendMessage(Utils::getConfigReplace("already_game"));
                        } else $sender->sendMessage(Utils::getConfigReplace("no_perm"));
                        break;
                    case "stop":
                        if ($sender->hasPermission(Utils::getConfigValue("admin_permission")) or $sender->getServer()->isOp($sender->getName())) {
                            if ($api->isGame()) {
                                $api->stopGame();
                                $sender->sendMessage(Utils::getConfigReplace("stop_game"));
                            } else $sender->sendMessage(Utils::getConfigReplace("no_game"));
                        } else $sender->sendMessage(Utils::getConfigReplace("no_perm"));
                        break;
                    case "join":
                        if ($api->isGame()) {
                            if (!$api->isInGame($sender)) {
                                if ($api->canJoin()) {
                                    $api->addPlayer($sender);
                                    $sender->sendMessage(Utils::getConfigReplace("join_game"));
                                } else $sender->sendMessage(Utils::getConfigReplace("no_join"));
                            } else $sender->sendMessage(Utils::getConfigReplace("no_join"));
                        } else $sender->sendMessage(Utils::getConfigReplace("no_game"));
                        break;
                    case "leave":
                        if ($api->isGame()) {
                            if ($api->isInGame($sender)) {
                                $api->removePlayer($sender);
                                $sender->teleport($sender->getServer()->getWorldManager()->getDefaultWorld()->getSafeSpawn());
                                $sender->sendMessage(Utils::getConfigReplace("leave_game"));
                            } else $sender->sendMessage(Utils::getConfigReplace("no_in_game"));
                        } else $sender->sendMessage(Utils::getConfigReplace("no_game"));
                        break;
                    default:
                        $sender->sendMessage(Utils::getConfigReplace("no_base_args"));
                        break;
                }
            } else $sender->sendMessage(Utils::getConfigReplace("no_base_args"));
        } else $sender->sendMessage(Utils::getConfigReplace("no_player"));
    }
}