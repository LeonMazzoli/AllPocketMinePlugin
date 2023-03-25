<?php

namespace Digueloulou12\CaveBlock;

use pocketmine\event\block\BlockPlaceEvent;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerQuitEvent;
use pocketmine\event\player\PlayerToggleSneakEvent;
use pocketmine\player\GameMode;
use pocketmine\player\Player;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\Config;

class CaveBlock extends PluginBase implements Listener
{
    public static array $players = [];

    public function onEnable(): void
    {
        $this->getServer()->getPluginManager()->registerEvents($this, $this);

        if (!file_exists($this->getDataFolder() . "config.yml")) {
            new Config($this->getDataFolder() . "config.yml", Config::YAML, [
                "block_id" => 1
            ]);
        }
    }

    public function onPlace(BlockPlaceEvent $event): void
    {
        if ($event->isCancelled()) return;
        if ($event->getBlock()->getId() === $this->getConfig()->get("block_id")) {
            $player = $event->getPlayer();
            $player->setImmobile(true);
            self::$players[$player->getName()] = [$player->getGamemode(), $player->getPosition()];
            $player->setGamemode(GameMode::SPECTATOR());
            $player->teleport($event->getBlock()->getPosition()->subtract(0, 2, 0));
        }
    }

    public function onSneak(PlayerToggleSneakEvent $event): void
    {
        $this->stop($event->getPlayer());
    }

    public function onQuit(PlayerQuitEvent $event): void
    {
        $this->stop($event->getPlayer());
    }

    public function stop(Player $player): void
    {
        if (isset(self::$players[$player->getName()])) {
            $player->setGamemode(self::$players[$player->getName()][0]);
            $player->setImmobile(false);
            $player->teleport(self::$players[$player->getName()][1]);
            unset(self::$players[$player->getName()]);
        }
    }
}