<?php

namespace Digueloulou12;

use pocketmine\event\player\PlayerChatEvent;
use pocketmine\command\CommandSender;
use pocketmine\plugin\PluginBase;
use pocketmine\command\Command;
use pocketmine\event\Listener;
use pocketmine\utils\Config;

class ChatLocker extends PluginBase implements Listener
{
    public static bool $lock = false;
    public static Config $config;

    public function onEnable(): void
    {
        # Config
        self::$config = new Config($this->getDataFolder() . "config.yml", Config::YAML, [
            "# command: [COMMAND, DESCRIPTION, PERMISSION]",
            "command" => ["chat", "ChatLocker Command", "chat.use"],
            "command_aliases" => [],
            "chat_lock" => "You blocked the chat !",
            "chat_unlock" => "You have unlocked the chat !",
            "no_chat" => "The chat is blocked !",
            "chat_lock_permission" => "chat.send"
        ]);

        # Event
        $this->getServer()->getPluginManager()->registerEvents($this, $this);

        # Command
        $this->getServer()->getCommandMap()->register("ChatLocker", new class extends Command {
            public function __construct()
            {
                parent::__construct(ChatLocker::$config->get("command")[0]);
                if (isset(ChatLocker::$config->get("command")[1])) $this->setDescription(ChatLocker::$config->get("command")[1]);
                if ((ChatLocker::$config->exists("command_aliases")) and (is_array(ChatLocker::$config->get("command_aliases")))) $this->setAliases(ChatLocker::$config->get("command_aliases"));
            }

            public function execute(CommandSender $sender, string $commandLabel, array $args)
            {
                $command = ChatLocker::$config->get("command");
                if ((isset($command[2])) and !($sender->hasPermission($command[2]))) return;

                if (ChatLocker::$lock) {
                    ChatLocker::$lock = false;
                } else ChatLocker::$lock = true;
                ChatLocker::$lock ? $sender->sendMessage(ChatLocker::$config->get("chat_lock")) : $sender->sendMessage(ChatLocker::$config->get("chat_unlock"));
            }
        });
    }

    public function onChat(PlayerChatEvent $event)
    {
        if ($event->getPlayer()->hasPermission(self::$config->get("chat_lock_permission"))) return;
        if (self::$lock) {
            $event->getPlayer()->sendMessage(self::$config->get("no_chat"));
            $event->cancel();
        }
    }
}