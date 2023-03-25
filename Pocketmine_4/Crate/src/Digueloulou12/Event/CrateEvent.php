<?php

namespace Digueloulou12\Event;

use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\console\ConsoleCommandSender;
use pocketmine\item\ItemFactory;
use pocketmine\event\Listener;
use pocketmine\lang\Language;
use Digueloulou12\Crate;
use pocketmine\Server;

class CrateEvent implements Listener
{
    public function onUse(PlayerInteractEvent $event)
    {
        if ($event->getBlock()->getId() === (int)Crate::getConfigValue("crate_id")) {
            if ($event->getItem()->getId() === (int)Crate::getConfigValue("crate_key_id")) {
                $event->cancel();
                if (!empty($event->getItem()->getLore())) {
                    if (Crate::getInstance()->getConfig()->getNested("crate.{$event->getItem()->getLore()[0]}") !== null) {
                        $rand = mt_rand(1, count(Crate::getInstance()->getConfig()->getNested("crate.{$event->getItem()->getLore()[0]}")));
                        $loot = explode(":", Crate::getInstance()->getConfig()->getNested("crate.{$event->getItem()->getLore()[0]}")[$rand - 1]);
                        $event->getPlayer()->sendMessage(Crate::getConfigReplace("crate_msg", ["{loot}", "{crate}"], [$loot[2], $event->getItem()->getLore()[0]]));
                        $event->getPlayer()->getInventory()->setItemInHand($event->getPlayer()->getInventory()->getItemInHand()->setCount($event->getItem()->getCount() - 1));
                        switch ($loot[0]) {
                            case "item":
                                $i = explode(".", $loot[1]);
                                $item = ItemFactory::getInstance()->get($i[0], $i[1], $i[2]);
                                if ($event->getPlayer()->getInventory()->canAddItem($item)) {
                                    $event->getPlayer()->getInventory()->addItem($item);
                                } else $event->getPlayer()->getWorld()->dropItem($event->getPlayer()->getPosition(), $item);
                                break;
                            case "command":
                                Server::getInstance()->getCommandMap()->dispatch(new ConsoleCommandSender(Server::getInstance(), new Language(Language::FALLBACK_LANGUAGE)), str_replace("{player}", $event->getPlayer()->getName(), $loot[1]));
                                break;
                        }
                    } else $event->getPlayer()->sendMessage(Crate::getConfigReplace("crate_msg_key"));
                } else $event->getPlayer()->sendMessage(Crate::getConfigReplace("crate_msg_key"));
            }
        }
    }
}