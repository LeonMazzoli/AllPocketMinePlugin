<?php

namespace Digueloulou12\LobbyCore\Commands;

use Digueloulou12\LobbyCore\Utils\Utils;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;

class ChatCommand extends Command
{
    public static bool $chat = false;

    public function __construct(string $name, string $description, array $aliases, ?string $permission)
    {
        parent::__construct($name, $description, null, $aliases);
        if (!is_null($permission)) $this->setPermission($permission);
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args)
    {
        if (!is_null($this->getPermission())) {
            if (!$sender->hasPermission($this->getPermission())) {
                return;
            }
        }

        self::$chat = !self::$chat;
        $status = self::$chat ? "on" : "off";
        $sender->sendMessage(Utils::getConfigReplace("chatCommandMessage", "{status}", $status));
    }
}