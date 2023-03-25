<?php

namespace Digueloulou12\Event;

use pocketmine\event\player\PlayerInteractEvent;
use Digueloulou12\Forms\EnchantmentForms;
use pocketmine\block\BlockLegacyIds;
use pocketmine\event\Listener;
use pocketmine\item\Armor;
use pocketmine\item\Bow;
use pocketmine\item\Sword;
use pocketmine\item\Tool;
use Digueloulou12\Table;

class EnchantmentEvent implements Listener
{
    public function onInteract(PlayerInteractEvent $event)
    {
        if ($event->getBlock()->getId() === BlockLegacyIds::ENCHANTING_TABLE) {
            $event->cancel();
            $item = $event->getItem();
            $config = Table::getInstance()->getConfig()->get("enchantments");
            if ($item instanceof Sword) {
                if ($config["sword"] !== null) {
                    $event->getPlayer()->sendForm(EnchantmentForms::listEnchants($event->getPlayer(), $config["sword"]));
                } else $event->getPlayer()->sendMessage(Table::getInstance()->getConfig()->get("no_enchant"));
            } elseif ($item instanceof Bow) {
                if ($config["bow"] !== null) {
                    $event->getPlayer()->sendForm(EnchantmentForms::listEnchants($event->getPlayer(), $config["bow"]));
                } else $event->getPlayer()->sendMessage(Table::getInstance()->getConfig()->get("no_enchant"));
            } elseif ($item instanceof Tool) {
                if ($config["tools"] !== null) {
                    $event->getPlayer()->sendForm(EnchantmentForms::listEnchants($event->getPlayer(), $config["tools"]));
                } else $event->getPlayer()->sendMessage(Table::getInstance()->getConfig()->get("no_enchant"));
            } elseif ($item instanceof Armor) {
                if ($config["armor"] !== null) {
                    $event->getPlayer()->sendForm(EnchantmentForms::listEnchants($event->getPlayer(), $config["armor"]));
                } else $event->getPlayer()->sendMessage(Table::getInstance()->getConfig()->get("no_enchant"));
            } else $event->getPlayer()->sendMessage(Table::getInstance()->getConfig()->get("no_enchant"));
        }
    }
}