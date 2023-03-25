<?php

namespace Digueloulou12\Totem\Events;

use Digueloulou12\Totem\Commands\TotemCommand;
use Digueloulou12\Totem\Utils\Utils;
use pocketmine\block\BlockFactory;
use pocketmine\console\ConsoleCommandSender;
use pocketmine\entity\effect\EffectInstance;
use pocketmine\entity\effect\VanillaEffects;
use pocketmine\event\block\BlockBreakEvent;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\item\Tool;
use pocketmine\Server;
use pocketmine\world\Position;

class TotemEvents implements Listener
{
    public function onBreak(BlockBreakEvent $event)
    {
        if (TotemCommand::$totem) {
            $pos = $event->getBlock()->getPosition();
            $pp = Utils::getConfigValue("totem_pos");
            if ($pp[0] === $pos->getX() and $pp[2] === $pos->getZ()) {
                $event->setDrops([]);
                $event->getPlayer()->getEffects()->remove(VanillaEffects::MINING_FATIGUE());
                if (in_array(self::getStringByPosition($event->getBlock()->getPosition()), TotemCommand::$blocks)) {
                    unset(TotemCommand::$blocks[array_search(self::getStringByPosition($event->getBlock()->getPosition()), TotemCommand::$blocks)]);
                }
                if (empty(TotemCommand::$blocks)) {
                    Server::getInstance()->broadcastMessage(Utils::getConfigReplace("totem_finish", "{player}", $event->getPlayer()->getName()));
                    foreach (Utils::getConfigValue("totem_commands") as $cmd) {
                        Server::getInstance()->getCommandMap()->dispatch(new ConsoleCommandSender(Server::getInstance(), Server::getInstance()->getLanguage()), str_replace("{player}", $event->getPlayer()->getName(), $cmd));
                    }
                    TotemCommand::$totem = false;
                    TotemCommand::$blocks = [];
                }
            }
        }
    }

    public function onInteract(PlayerInteractEvent $event)
    {
        if (TotemCommand::$totem) {
            $id = [$event->getBlock()->getId(), $event->getBlock()->getMeta()];
            if (Utils::getConfigValue("totem_block") === $id) {
                $pos = $event->getBlock()->getPosition();
                $pp = Utils::getConfigValue("totem_pos");
                if ($pp[0] === $pos->getX() and $pp[2] === $pos->getZ()) {
                    $event->getPlayer()->getEffects()->add(new EffectInstance(VanillaEffects::MINING_FATIGUE(), 20 * 10, 3, false));
                }
            } else $event->getPlayer()->getEffects()->remove(VanillaEffects::MINING_FATIGUE());
        }
    }

    public static function getStringByPosition(Position $position): string
    {
        return "{$position->x}!{$position->y}!{$position->z}!{$position->getWorld()->getFolderName()}";
    }
}