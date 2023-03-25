<?php

namespace Digueloulou12;

use pocketmine\block\inventory\DoubleChestInventory;
use pocketmine\event\player\PlayerInteractEvent;
use muqsit\invmenu\type\InvMenuTypeIds;
use pocketmine\block\BlockLegacyIds;
use muqsit\invmenu\InvMenuHandler;
use pocketmine\plugin\PluginBase;
use pocketmine\block\tile\Chest;
use pocketmine\event\Listener;
use pocketmine\math\Vector3;
use muqsit\invmenu\InvMenu;

class ChestClaim extends PluginBase implements Listener
{
    public function onEnable(): void
    {
        $this->saveDefaultConfig();

        if (!InvMenuHandler::isRegistered()) {
            InvMenuHandler::register($this);
        }

        $this->getServer()->getPluginManager()->registerEvents($this, $this);
    }

    /** @priority HIGHEST */
    public function onInteract(PlayerInteractEvent $event)
    {
        if ($event->getBlock()->getId() === BlockLegacyIds::CHEST) {
            if ($event->getItem()->getId() === $this->getConfig()->get("item")) {
                if ($event->isCancelled()) {
                    $tile = $event->getBlock()->getPosition()->getWorld()->getTile(new Vector3($event->getBlock()->getPosition()->x, $event->getBlock()->getPosition()->y, $event->getBlock()->getPosition()->z));
                    if ($tile instanceof Chest) {
                        $inv = $tile->getInventory();
                        if ($inv instanceof DoubleChestInventory) {
                            $menu = InvMenu::create(InvMenuTypeIds::TYPE_DOUBLE_CHEST);
                        } else $menu = InvMenu::create(InvMenuTypeIds::TYPE_CHEST);
                        $menu->getInventory()->setContents($tile->getInventory()->getContents());
                        $menu->setListener(InvMenu::readonly());
                        $menu->send($event->getPlayer());

                        $item = $event->getItem();
                        if (isset($item->getLore()[0])) {
                            if ($item->getLore()[0] == 1) {
                                $event->getPlayer()->getInventory()->setItemInHand($item->setCount($item->getCount() - 1));
                            } else {
                                $event->getPlayer()->getInventory()->setItemInHand($item->setCount($item->getCount() - 1));
                                $new = $item->setLore([$item->getLore()[0] - 1]);
                                if ($event->getPlayer()->getInventory()->canAddItem($new)) {
                                    $event->getPlayer()->getInventory()->addItem($new);
                                } else $event->getPlayer()->getWorld()->dropItem($event->getPlayer()->getPosition(), $new);
                            }
                        } else {
                            $event->getPlayer()->getInventory()->setItemInHand($item->setCount($item->getCount() - 1));
                            $new = $item->setLore([$this->getConfig()->get("number_use")]);
                            if ($event->getPlayer()->getInventory()->canAddItem($new)) {
                                $event->getPlayer()->getInventory()->addItem($new);
                            } else $event->getPlayer()->getWorld()->dropItem($event->getPlayer()->getPosition(), $new);
                        }
                    }
                }
            }
        }
    }
}