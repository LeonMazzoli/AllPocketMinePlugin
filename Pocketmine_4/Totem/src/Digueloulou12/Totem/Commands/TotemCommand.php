<?php

namespace Digueloulou12\Totem\Commands;

use Digueloulou12\Totem\Events\TotemEvents;
use Digueloulou12\Totem\Utils\Utils;
use pocketmine\block\BlockFactory;
use pocketmine\block\VanillaBlocks;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\Server;
use pocketmine\world\Position;

class TotemCommand extends Command
{
    public static array $blocks = [];
    public static bool $totem = false;

    public function __construct(string $name, string $description, array $aliases)
    {
        parent::__construct($name, $description, null, $aliases);
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args)
    {
        if (!Utils::hasPermissionPlayer($sender, "totem")) return;

        if (isset($args[0])) {
            switch ($args[0]) {
                case "start":
                    if (!self::$totem) {
                        $pos = Utils::getConfigValue("totem_pos");
                        $world = Server::getInstance()->getWorldManager()->getWorldByName($pos[3]);
                        $block = BlockFactory::getInstance()->get(Utils::getConfigValue("totem_block")[0], Utils::getConfigValue("totem_block")[1]);
                        for ($y = $pos[1]; $y !== $pos[1] + 5; $y++) {
                            $world->setBlockAt($pos[0], $y, $pos[2], $block);
                            self::$blocks[] = TotemEvents::getStringByPosition(new Position($pos[0], $y, $pos[2], $world));
                        }
                        Server::getInstance()->broadcastMessage(Utils::getConfigReplace("totem_start"));
                        self::$totem = true;
                    } else $sender->sendMessage(Utils::getConfigReplace("totem_no_start"));
                    break;
                case "stop":
                    if (self::$totem) {
                        self::$totem = false;
                        $pos = Utils::getConfigValue("totem_pos");
                        $world = Server::getInstance()->getWorldManager()->getWorldByName($pos[3]);
                        foreach (self::$blocks as $block) {
                            $world->setBlock(self::getPositionByString($block), VanillaBlocks::AIR());
                        }
                        self::$blocks = [];
                        Server::getInstance()->getWorldManager()->getWorldByName($pos[3])->setBlockAt($pos[0], $pos[1], $pos[2], BlockFactory::getInstance()->get(0, 0));
                        Server::getInstance()->broadcastMessage(Utils::getConfigReplace("totem_stop"));
                    } else $sender->sendMessage(Utils::getConfigReplace("totem_no_stop"));
                    break;
                default:
                    $sender->sendMessage(Utils::getConfigReplace("totem_no_args"));
                    break;
            }
        } else $sender->sendMessage(Utils::getConfigReplace("totem_no_args"));
    }

    public static function getPositionByString(string $position): Position
    {
        $pos = explode("!", $position);
        return new Position(intval($pos[0]), intval($pos[1]), intval($pos[2]), Server::getInstance()->getWorldManager()->getWorldByName(strval($pos[3])));
    }
}