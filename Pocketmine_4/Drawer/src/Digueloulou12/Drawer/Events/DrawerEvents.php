<?php

namespace Digueloulou12\Drawer\Events;

use Digueloulou12\Drawer\Drawer;
use Digueloulou12\Drawer\Forms\DrawerForm;
use Digueloulou12\Drawer\Utils\Utils;
use pocketmine\event\block\BlockBreakEvent;
use pocketmine\event\block\BlockPlaceEvent;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\item\Tool;

class DrawerEvents implements Listener
{
    public function onPlace(BlockPlaceEvent $event)
    {
        $block = $event->getBlock();
        $api = Drawer::getInstance()->getAPI();
        if ($api->isDrawer($block)) {
            $item = $event->getItem();
            if ($item->getNamedTag()->getTag("item") !== null) {
                $api->createDrawer($block->getPosition(), $block, $item->getNamedTag()->getString("item"), $item->getNamedTag()->getInt("max"));
            } else $api->createDrawer($block->getPosition(), $block);
        }
    }

    public function onInteract(PlayerInteractEvent $event)
    {
        $block = $event->getBlock();
        $api = Drawer::getInstance()->getAPI();
        if ($api->isDrawer($block)) {
            $player = $event->getPlayer();
            if ($api->existDrawer($block->getPosition())) {
                $action = $event->getAction();
                $drawer = $api->getDrawer($block->getPosition());
                if ($action === PlayerInteractEvent::LEFT_CLICK_BLOCK) {
                    $item = $event->getItem();
                    if (($item instanceof Tool) and ($player->isSneaking())) return;
                    if ($item->getId() !== 0) {
                        $count = $player->isSneaking() ? $item->getCount() : 1;
                        if (((serialize($drawer->getItem()->setCount(1)->jsonSerialize()) === serialize($item->setCount(1)->jsonSerialize()))) or ($drawer->isEmpty())) {
                            if ($drawer->canAddItem($item->setCount($count))) {
                                $drawer->addItem($item->setCount($count));
                                $player->getInventory()->removeItem($item->setCount($count));
                                $player->sendPopup(Utils::getConfigReplace("popup_add", ["{count}", "{max_count}"], [$drawer->getItem()->getCount(), $drawer->getMaxItem()]));
                            } else $player->sendMessage(Utils::getConfigReplace("no_place"));
                        } else $player->sendMessage(Utils::getConfigReplace("one_item"));
                    } else $player->sendMessage(Utils::getConfigReplace("no_item"));
                } else $player->sendForm(DrawerForm::drawerForm($drawer));
            }
        }
    }

    public function onBreak(BlockBreakEvent $event)
    {
        $block = $event->getBlock();
        $api = Drawer::getInstance()->getAPI();
        if ($api->isDrawer($block)) {
            if ($api->existDrawer($block->getPosition())) {
                $player = $event->getPlayer();
                $item = $player->getInventory()->getItemInHand();
                if ($item->getId() !== 0) {
                    if ($item->getId() === Utils::getConfigValue("item_break")) {
                        $drawer = $api->getDrawer($block->getPosition());
                        $item = $event->getItem();
                        if (($item instanceof Tool) and ($player->isSneaking())) return;
                        $count = $player->isSneaking() ? $item->getCount() : 1;
                        if (((serialize($drawer->getItem()->setCount(1)->jsonSerialize()) === serialize($item->setCount(1)->jsonSerialize()))) or ($drawer->isEmpty())) {
                            if ($drawer->canAddItem($item->setCount($count))) {
                                $drawer->addItem($item->setCount($count));
                                $player->getInventory()->removeItem($item->setCount($count));
                                $player->sendPopup(Utils::getConfigReplace("popup_add", ["{count}", "{max_count}"], [$drawer->getItem()->getCount(), $drawer->getMaxItem()]));
                            } else $player->sendMessage(Utils::getConfigReplace("no_place"));
                        } else $player->sendMessage(Utils::getConfigReplace("one_item"));
                        $event->cancel();
                        return;
                    }
                }

                $event->setDrops([$api->removeDrawer($block->getPosition(), $block)]);
            }
        }
    }
}