<?php

namespace Digueloulou12\MathChat;

use pocketmine\console\ConsoleCommandSender;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerChatEvent;
use pocketmine\plugin\PluginBase;
use pocketmine\scheduler\Task;
use pocketmine\Server;
use pocketmine\utils\Config;

class MathChat extends PluginBase implements Listener
{
    public static ?string $math = null;

    public function onEnable(): void
    {
        if (!file_exists($this->getDataFolder() . "config.yml")) {
            new Config($this->getDataFolder() . "config.yml", Config::YAML, [
                "time" => 300,
                "math" => [
                    "1+1" => 2,
                    "2+2" => 4
                ],
                "win_command" => "give {player} diamond",
                "win_message" => "The player {player} has found the answer to the calculation {math} which was {result}!",
                "message_server" => "The first player to find the {math} calculation wins 64 diamonds!"
            ]);
        }

        $this->getServer()->getPluginManager()->registerEvents($this, $this);

        $this->getScheduler()->scheduleRepeatingTask(new class($this->getConfig()->get("message_server"), $this->getConfig()->get("math")) extends Task
        {
            public function __construct(public string $message, public array $math)
            {
            }

            public function onRun(): void
            {
                $array = $this->math;
                $array = array_keys($array);
                $math = $array[array_rand($array)];
                MathChat::$math = $math;
                Server::getInstance()->broadcastMessage(str_replace("{math}", $math, $this->message));
            }
        }, 20 * $this->getConfig()->get("time"));
    }

    public function onChat(PlayerChatEvent $event)
    {
        if (!is_null(self::$math)) {
            if ($event->getMessage() === strval($this->getConfig()->get("math")[self::$math])) {
                $this->getServer()->broadcastMessage(str_replace(["{math}", "{result}", "{player}"], [self::$math, $this->getConfig()->get("math")[self::$math], $event->getPlayer()->getName()], $this->getConfig()->get("win_message")));
                $this->getServer()->getCommandMap()->dispatch(new ConsoleCommandSender($this->getServer(), $this->getServer()->getLanguage()), str_replace("{player}", $event->getPlayer()->getName(), $this->getConfig()->get("win_command")));
                self::$math = null;
            }
        }
    }
}