<?php

namespace Digueloulou12\LobbyCore\Commands;

use Digueloulou12\LobbyCore\Entities\NpcEntity;
use Digueloulou12\LobbyCore\Utils\Utils;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\player\Player;

class NpcCommand extends Command
{
    public static array $id = [];

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

        if (!($sender instanceof Player)) {
            $sender->sendMessage(Utils::getConfigReplace("no_player"));
            return;
        }

        if (isset($args[0])) {
            switch (strtolower($args[0])) {
                case "add":
                case "create":
                    if (isset($args[1])) {
                        $port = $args[2] ?? 19132;
                        $name = $args[3] ?? "";
                        $nbt = new CompoundTag();
                        $nbt
                            ->setString("NameTag", $args[1])
                            ->setString("ip", $args[1])
                            ->setInt("port", $port)
                            ->setFloat("customScale", 3.0)
                            ->setString("customName", $name);
                        $entity = new NpcEntity($sender->getLocation(), $sender->getSkin(), $nbt);
                        $entity->spawnToAll();
                        $sender->sendMessage(Utils::getConfigReplace("npc_spawn", ["{ip}", "{port}", "{name}"], [$args[1], $port, $name]));
                    } else $sender->sendMessage(Utils::getConfigReplace("no_ip"));
                    break;
                case "id":
                    if (!in_array($sender->getName(), self::$id)) {
                        self::$id[] = $sender->getName();
                        $sender->sendMessage(Utils::getConfigReplace("id_msg_"));
                    } else $sender->sendMessage(Utils::getConfigReplace("already_id"));
                    break;
                case "del":
                case "remove":
                case "delete":
                    if (isset($args[1]) and is_numeric($args[1])) {
                        $entity = $sender->getWorld()->getEntity($args[1]);
                        if ($entity instanceof NpcEntity) {
                            $entity->flagForDespawn();
                        }
                    }
                    break;
                default:
                    $sender->sendMessage(Utils::getConfigReplace("no_action"));
                    break;
            }
        } else $sender->sendMessage(Utils::getConfigReplace("no_action"));
    }
}