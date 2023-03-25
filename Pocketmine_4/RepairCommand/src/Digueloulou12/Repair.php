<?php

namespace Digueloulou12;

use pocketmine\command\CommandSender;
use pocketmine\plugin\PluginBase;
use pocketmine\command\Command;
use pocketmine\player\Player;
use pocketmine\utils\Config;
use pocketmine\item\Armor;
use pocketmine\item\Item;
use pocketmine\item\Tool;

class Repair extends PluginBase
{
    public function onEnable(): void
    {
        if (!file_exists($this->getDataFolder() . "config.yml")) {
            new Config($this->getDataFolder() . "config.yml", Config::YAML, [
                "command" => ["repair", "RepairCommand"],
                "command_aliases" => ["re"],
                "commandAll" => ["repairall", "RepairAllCommand"],
                "commandAll_aliases" => ["rea"],
                "item_repair" => "You have successfully repaired your items!",
                "no_repair" => "You cannot repair this item!",
                "no_time" => "You must wait {min} minute(s) !",
                "no_perm" => "You don't have permission!"
            ]);
        }

        $this->getServer()->getCommandMap()->register("RepairCommand", new class($this->getConfig()->get("command")[0], $this->getConfig()->get("command")[1] ?? "", $this->getConfig()->get("command_aliases"), $this->getConfig()) extends Command {
            private array $time = [];
            private Config $config;

            public function __construct(string $name, string $description, array $aliases, Config $config)
            {
                parent::__construct($name, $description, null, $aliases);
                $this->config = $config;
            }

            public function execute(CommandSender $sender, string $commandLabel, array $args)
            {
                if ($sender instanceof Player) {
                    $time = null;
                    foreach ($sender->getEffectivePermissions() as $permission) {
                        $perm = explode(".", $permission->getPermission());
                        if ($perm[0] === "repair") {
                            if (isset($perm[1]) and is_numeric(explode(".", $permission->getPermission())[1])) {
                                $time = explode(".", $permission->getPermission())[1];
                                break;
                            }
                        }
                    }

                    if (!is_null($time)) {
                        if (!(isset($this->time[$sender->getName()])) or ($this->time[$sender->getName()] < time())) {
                            if (Repair::repairItem($sender->getInventory()->getItemInHand(), 0, $sender, true)) {
                                $sender->sendMessage($this->config->get("item_repair"));
                                $this->time[$sender->getName()] = (int)$time + time();
                            } else $sender->sendMessage($this->config->get("no_repair"));
                        } else {
                            $min = $this->time[$sender->getName()] - time();
                            $min = ceil($min / 60);
                            $sender->sendMessage(str_replace("{min}", $min, $this->config->get("no_time")));
                        }
                    } else $sender->sendMessage($this->config->get("no_perm"));
                }
            }
        });

        $this->getServer()->getCommandMap()->register("RepairCommand", new class($this->getConfig()->get("commandAll")[0], $this->getConfig()->get("commandAll")[1] ?? "", $this->getConfig()->get("commandAll_aliases"), $this->getConfig()) extends Command {
            private array $time_all = [];
            private Config $config;

            public function __construct(string $name, string $description, array $aliases, Config $config)
            {
                parent::__construct($name, $description, null, $aliases);
                $this->config = $config;
            }

            public function execute(CommandSender $sender, string $commandLabel, array $args)
            {
                if ($sender instanceof Player) {
                    $time_all = null;
                    foreach ($sender->getEffectivePermissions() as $permission) {
                        $perm = explode(".", $permission->getPermission());
                        if ($perm[0] === "repairall") {
                            if (isset($perm[1]) and is_numeric(explode(".", $permission->getPermission())[1])) {
                                $time_all = explode(".", $permission->getPermission())[1];
                                break;
                            }
                        }
                    }

                    if (!is_null($time_all)) {
                        if (!(isset($this->time_all[$sender->getName()])) or ($this->time_all[$sender->getName()] < time())) {
                            foreach ($sender->getInventory()->getContents() as $slot => $item) {
                                Repair::repairItem($item, $slot, $sender);
                            }
                            $sender->sendMessage($this->config->get("item_repair"));
                            $this->time_all[$sender->getName()] = (int)$time_all + time();
                        } else {
                            $min = $this->time_all[$sender->getName()] - time();
                            $min = ceil($min / 60);
                            $sender->sendMessage(str_replace("{min}", $min, $this->config->get("no_time")));
                        }
                    } else $sender->sendMessage($this->config->get("no_perm"));
                }
            }
        });
    }

    public static function repairItem(Item $item, int $slot, Player $player, bool $inHand = false): bool
    {
        if (($item instanceof Tool) or ($item instanceof Armor)) {
            if ($item->getMeta() > 0) {
                $item->setDamage(0);
                if ($item->getNamedTag()->getTag("Durabilité") !== null) $item->getNamedTag()->setString("Durabilité", $item->getMaxDurability());
                $inHand ? $player->getInventory()->setItemInHand($item) : $player->getInventory()->setItem($slot, $item);
                return true;
            } else return false;
        } else return false;
    }
}