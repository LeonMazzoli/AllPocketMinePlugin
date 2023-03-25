<?php

namespace Digueloulou12\Koth\Command;

use Digueloulou12\Koth\Koth;
use Digueloulou12\Koth\Task\KothTask;
use Digueloulou12\Koth\Utils\Utils;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\Server;

class KothCommand extends Command
{
    public static KothTask $kothTask;
    public static bool $koth = false;

    public function __construct(string $name, string $description, array $aliases)
    {
        parent::__construct($name, $description, null, $aliases);
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args)
    {
        if (!Utils::hasPermissionPlayer($sender, "koth")) return;

        if (isset($args[0])) {
            switch ($args[0]) {
                case "start":
                    if (!self::$koth) {
                        $koth = new KothTask();
                        self::$koth = true;
                        self::$kothTask = $koth;
                        Koth::getInstance()->getScheduler()->scheduleDelayedRepeatingTask($koth, 20, 20);
                        Server::getInstance()->broadcastMessage(Utils::getConfigReplace("koth_start"));
                    } else $sender->sendMessage(Utils::getConfigReplace("koth_no_start"));
                    break;
                case "stop":
                    if (self::$koth) {
                        self::$koth = false;
                        self::$kothTask->getHandler()->cancel();
                        Server::getInstance()->broadcastMessage(Utils::getConfigReplace("koth_stop"));
                    } else $sender->sendMessage(Utils::getConfigReplace("koth_no_stop"));
                    break;
                default:
                    $sender->sendMessage(Utils::getConfigReplace("koth_no_args"));
                    break;
            }
        } else $sender->sendMessage(Utils::getConfigReplace("koth_no_args"));
    }
}