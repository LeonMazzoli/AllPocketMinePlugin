<?php

namespace Digueloulou12\BlockMoney;

use onebone\economyapi\EconomyAPI;
use pocketmine\event\block\BlockBreakEvent;
use pocketmine\event\Listener;
use pocketmine\plugin\PluginBase;

class BlockMoney extends PluginBase implements Listener
{
    public function onEnable(): void
    {
        $this->saveDefaultConfig();
        $this->getServer()->getPluginManager()->registerEvents($this, $this);
    }

    public function onBreak(BlockBreakEvent $event): void
    {
        $player = $event->getPlayer();
        $block = $event->getBlock();
        $config = $this->getConfig();

        $value = "{$block->getId()}-{$block->getMeta()}";
        if ($config->exists($value)) {
            $money = mt_rand($config->get($value)[0], $config->get($value)[1]);
            EconomyAPI::getInstance()->addMoney($player, $money);
            $player->sendPopup(str_replace("{money}", $money, $config->get("message")));
        }
    }
}