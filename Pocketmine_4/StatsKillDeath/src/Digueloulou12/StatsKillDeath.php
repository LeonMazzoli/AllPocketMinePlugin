<?php

namespace Digueloulou12;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerDeathEvent;
use pocketmine\player\Player;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\Config;

class StatsKillDeath extends PluginBase implements Listener
{
    public Config $data;

    public function onEnable(): void
    {
        if (!file_exists($this->getDataFolder() . "config.yml")) {
            new Config($this->getDataFolder() . "config.yml", Config::YAML, [
                "command" => ["stats", "Stats Command"],
                "command_aliases" => [],
                "message" => "Kill: {kill}\nDeath: {death}\nRatio: {ratio}"
            ]);
        }

        $this->data = new Config($this->getDataFolder() . "StatsData.json", Config::JSON);

        $this->getServer()->getPluginManager()->registerEvents($this, $this);
        $this->getServer()->getCommandMap()->register("StatsKillDeath", new class($this->getConfig()->get("command")[0], $this->getConfig()->get("command")[1] ?? "", $this->getConfig()->get("command_aliases"), $this->data, $this->getConfig()->get("message")) extends Command {
            private string $message;
            private Config $data;

            public function __construct(string $name, string $description, array $aliases, Config $data, string $message)
            {
                $this->data = $data;
                $this->message = $message;
                parent::__construct($name, $description, null, $aliases);
            }

            public function execute(CommandSender $sender, string $commandLabel, array $args)
            {
                if ($sender instanceof Player) {
                    $data = $this->data;
                    if (isset($args[0]) and $data->exists($args[0])) $name = $args[0]; else $name = $sender->getName();
                    if (!$data->exists($name)) $data->set($name, ["kill" => 0, "death" => 0]);
                    $kill = $data->get($name)["kill"];
                    $death = $data->get($name)["death"];
                    if ($death === 0) $death = 1;
                    $ratio = $kill / $death;
                    $sender->sendMessage(str_replace(["{kill}", "{death}", "{ratio}"], [$kill, $data->get($name)["death"], $ratio], $this->message));
                }
            }
        });
    }

    public function onDisable(): void
    {
        $this->data->save();
    }

    public function onDeath(PlayerDeathEvent $event)
    {
        $player = $event->getEntity();

        if ($player instanceof Player) {
            if (!$this->data->exists($player->getName())) $this->data->set($player->getName(), ["kill" => 0, "death" => 0]);
            $this->data->set($player->getName(), ["kill" => $this->data->get($player->getName())["kill"], "death" => $this->data->get($player->getName())["death"] + 1]);
            $cause = $player->getLastDamageCause();
            if ($cause instanceof EntityDamageByEntityEvent) {
                $sender = $cause->getDamager();
                if ($sender instanceof Player) {
                    if (!$this->data->exists($sender->getName())) $this->data->set($sender->getName(), ["kill" => 0, "death" => 0]);
                    $this->data->set($sender->getName(), ["kill" => $this->data->get($sender->getName())["kill"] + 1, "death" => $this->data->get($sender->getName())["death"]]);
                }
            }
        }
    }
}