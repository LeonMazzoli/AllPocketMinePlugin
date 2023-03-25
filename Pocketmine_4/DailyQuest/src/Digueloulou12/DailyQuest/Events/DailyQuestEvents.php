<?php

namespace Digueloulou12\DailyQuest\Events;

use Digueloulou12\DailyQuest\API\DailyQuestAPI;
use Digueloulou12\DailyQuest\API\DailyQuestType;
use pocketmine\event\block\BlockBreakEvent;
use pocketmine\event\block\BlockPlaceEvent;
use pocketmine\event\inventory\CraftItemEvent;
use pocketmine\event\Listener;

class DailyQuestEvents implements Listener
{
    public function onCraft(CraftItemEvent $event)
    {
        $player = $event->getPlayer();
        foreach ($event->getOutputs() as $item) {
            if (DailyQuestAPI::isStartQuest($player)) {
                if (DailyQuestAPI::getTypeOfQuest() === DailyQuestType::CRAFT) {
                    if (($item->getId() === DailyQuestAPI::getBlockItemOfQuest()->getId()) and
                        ($item->getMeta() === DailyQuestAPI::getBlockItemOfQuest()->getMeta())) {
                        DailyQuestAPI::updatePlayer($player, $item->getCount());
                    }
                }
            }
        }
    }

    public function onBreak(BlockBreakEvent $event)
    {
        $player = $event->getPlayer();
        if (DailyQuestAPI::isStartQuest($player)) {
            if (DailyQuestAPI::getTypeOfQuest() === DailyQuestType::BREAK) {
                if (($event->getBlock()->getId() === DailyQuestAPI::getBlockItemOfQuest()->getId()) and
                    ($event->getBlock()->getMeta() === DailyQuestAPI::getBlockItemOfQuest()->getMeta())) {
                    DailyQuestAPI::updatePlayer($player);
                }
            }
        }
    }

    public function onPlace(BlockPlaceEvent $event)
    {
        $player = $event->getPlayer();
        if (DailyQuestAPI::isStartQuest($player)) {
            if (DailyQuestAPI::getTypeOfQuest() === DailyQuestType::PLACE) {
                if (($event->getBlock()->getId() === DailyQuestAPI::getBlockItemOfQuest()->getId()) and
                    ($event->getBlock()->getMeta() === DailyQuestAPI::getBlockItemOfQuest()->getMeta())) {
                    DailyQuestAPI::updatePlayer($player);
                }
            }
        }
    }
}