<?php

namespace Digueloulou12;

use pocketmine\command\CommandSender;
use pocketmine\player\Player;
use pocketmine\plugin\PluginBase;
use pocketmine\command\Command;
use pocketmine\Server;
use pocketmine\permission\Permission;
use pocketmine\permission\PermissionManager;
use pocketmine\utils\Config;
use pocketmine\world\Position;

class TeleportationCommands extends PluginBase
{
    public function onEnable(): void
    {
        if (!file_exists($this->getDataFolder() . "config.yml")) {
            new Config($this->getDataFolder() . "config.yml", Config::YAML, [
                "custom" => [
                    "description" => "Teleport to custom position",
                    "permission" => null,
                    "message" => "You have been teleported to custom position !",
                    "aliases" => ["custom_position"],
                    "position" => [100, 100, 100, "world"]
                ],
                "spawn" => [
                    "description" => null,
                    "permission" => null,
                    "message" => null,
                    "aliases" => null,
                    "position" => ["{x}", "{y}", "{z}", "{default_world}"],
                    "information" => ["{x}" => "Default X of world", "{y}" => "Default Y of world", "{z}" => "Default Z of world", "{default_world}" => "Default World of Server"]
                ]
            ]);
        }

        foreach ($this->getConfig()->getAll() as $command => $value) {
            if (!is_null($value["permission"])) PermissionManager::getInstance()->addPermission(new Permission($value["permission"]));

            $this->getServer()->getCommandMap()->register("TeleportationCommands", new class($command, is_null($value["description"]) ? "" : $value["description"], "/" . $command, is_null($value["aliases"]) ? [] : $value["aliases"], $value["permission"], $value["position"], $value["message"]) extends Command {
                public ?string $message;
                public array $position;

                public function __construct(string $name, string $description, string $usageMessage, array $aliases, ?string $permission, array $position, ?string $message)
                {
                    $this->message = $message;
                    $this->position = $position;
                    parent::__construct($name, $description, $usageMessage, $aliases);
                    if (!is_null($permission)) $this->setPermission($permission);
                }

                public function execute(CommandSender $sender, string $commandLabel, array $args)
                {
                    if ($sender instanceof Player) {
                        if ((Server::getInstance()->isOp($sender->getName())) or ($sender->hasPermission($this->getPermission()))) {
                            $world = isset($this->position[3]) ? Server::getInstance()->getWorldManager()->getWorldByName(str_replace("{default_world}", Server::getInstance()->getWorldManager()->getDefaultWorld()->getFolderName(), $this->position[3])) : $sender->getWorld();
                            $x = intval(str_replace("{x}", $world->getSafeSpawn()->getX(), $this->position[0]));
                            $y = intval(str_replace("{y}", $world->getSafeSpawn()->getY(), $this->position[1]));
                            $z = intval(str_replace("{z}", $world->getSafeSpawn()->getZ(), $this->position[2]));
                            $position = new Position($x, $y, $z, $world);
                            if ($position->isValid()) {
                                $sender->teleport($position);
                                if (!is_null($this->message)) {
                                    $sender->sendMessage($this->message);
                                }
                            }
                        }
                    }
                }
            });
        }
    }
}