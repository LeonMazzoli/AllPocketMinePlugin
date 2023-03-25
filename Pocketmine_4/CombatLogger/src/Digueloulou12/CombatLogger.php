<?php

namespace Digueloulou12;

use pocketmine\event\player\PlayerCommandPreprocessEvent;
use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\event\player\PlayerQuitEvent;
use pocketmine\plugin\PluginBase;
use pocketmine\scheduler\Task;
use pocketmine\event\Listener;
use pocketmine\player\Player;
use pocketmine\utils\Config;
use pocketmine\Server;

class CombatLogger extends PluginBase implements Listener
{
    public static array $players = [];

    public function onEnable(): void
    {
        if (!file_exists($this->getDataFolder() . "config.yml")) {
            new Config($this->getDataFolder() . "config.yml", Config::YAML, [
                "time" => 15,
                "popup" => "You are in combat!",
                "no_command" => "You cannot execute commands in combat!",
                "permission" => "combat.bypass",
                "ban_commands" => ["/lobby", "/heal", "/teleport", "/"]
            ]);
        }

        $this->getServer()->getPluginManager()->registerEvents($this, $this);

        $this->getScheduler()->scheduleRepeatingTask(new class($this->getConfig()) extends Task {
            private Config $config;

            public function __construct(Config $config)
            {
                $this->config = $config;
            }

            public function onRun(): void
            {
                foreach (CombatLogger::$players as $player_ => $time) {
                    $player = Server::getInstance()->getPlayerByPrefix($player_);
                    if (($time - time()) > 0) {
                        $player->sendPopup($this->config->get("popup"));
                    } else unset(CombatLogger::$players[$player_]);
                }
            }
        }, 20);
    }

    public function onDamage(EntityDamageByEntityEvent $event)
    {
        $player = $event->getEntity();
        $sender = $event->getDamager();

        if (!($player instanceof Player)) return;
        if (!($sender instanceof Player)) return;

        if ($event->isCancelled()) return;

        self::$players[$player->getName()] = time() + $this->getConfig()->get("time");
        self::$players[$sender->getName()] = time() + $this->getConfig()->get("time");
    }

    public function onChat(PlayerCommandPreprocessEvent $event)
    {
        if (isset(self::$players[$event->getPlayer()->getName()])) {
            if (in_array(explode(" ", $event->getMessage())[0], $this->getConfig()->get("ban_commands"))) {
                if ($this->getServer()->isOp($event->getPlayer()->getName())) return;

                if (!$event->getPlayer()->hasPermission($this->getConfig()->get("permission"))) {
                    $event->getPlayer()->sendMessage($this->getConfig()->get("no_command"));
                    $event->cancel();
                }
            }
        }
    }

    public function onQuit(PlayerQuitEvent $event)
    {
        if (isset(self::$players[$event->getPlayer()->getName()])) {
            unset(self::$players[$event->getPlayer()->getName()]);
            $event->getPlayer()->kill();
        }
    }
}