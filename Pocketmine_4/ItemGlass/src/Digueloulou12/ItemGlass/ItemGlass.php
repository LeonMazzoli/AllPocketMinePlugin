<?php

namespace Digueloulou12\ItemGlass;

use pocketmine\block\BlockFactory;
use pocketmine\block\BlockLegacyIds;
use pocketmine\block\Glass;
use pocketmine\event\block\BlockBreakEvent;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerItemUseEvent;
use pocketmine\plugin\PluginBase;
use pocketmine\scheduler\ClosureTask;
use pocketmine\Server;
use pocketmine\utils\Config;
use pocketmine\world\Position;

class ItemGlass extends PluginBase implements Listener
{
    public array $blocks = [];
    public array $time = [];

    public function onEnable(): void
    {
        $this->getServer()->getPluginManager()->registerEvents($this, $this);

        if (!file_exists($this->getDataFolder() . "config.yml")) {
            new Config($this->getDataFolder() . "config.yml", Config::YAML, [
                "glass_time" => 10,
                "cooldown" => 15,
                "popup" => "Vous devez attentre encore {time} !",
                "item" => [369, 0]
            ]);
        }
    }

    public function onBreak(BlockBreakEvent $event)
    {
        $block = $event->getBlock();
        if (in_array($this->getStringByPosition($block->getPosition()), $this->blocks)) {
            $event->cancel();
        }
    }

    public function onHit(PlayerItemUseEvent $event)
    {
        $player = $event->getPlayer();

        $item = $event->getItem();
        $ii = $this->getConfig()->get("item");
        if (($item->getId() === $ii[0]) and ($item->getMeta() === $ii[1] ?? 0)) {
            if (isset($this->time[$player->getName()]) and $this->time[$player->getName()] > time()) {
                $time = $this->time[$player->getName()] - time();
                $player->sendPopup(str_replace("{time}", $time, $this->getConfig()->get("popup")));
                return;
            }

            $this->time[$player->getName()] = time() + $this->getConfig()->get("cooldown");

            $x = round($player->getPosition()->getX());
            $y = round($player->getPosition()->getY());
            $z = round($player->getPosition()->getZ());
            $world = $player->getWorld();

            $blocks = [
                [$x + 1, $y - 1, $z],
                [$x - 1, $y - 1, $z],
                [$x + 1, $y - 1, $z + 1],
                [$x - 1, $y - 1, $z - 1],
                [$x + 1, $y - 1, $z - 1],
                [$x - 1, $y - 1, $z + 1],
                [$x, $y - 1, $z],
                [$x, $y - 1, $z + 1],
                [$x, $y - 1, $z - 1],

                [$x + 1, $y + 2, $z],
                [$x - 1, $y + 2, $z],
                [$x + 1, $y + 2, $z + 1],
                [$x - 1, $y + 2, $z - 1],
                [$x + 1, $y + 2, $z - 1],
                [$x - 1, $y + 2, $z + 1],
                [$x, $y + 2, $z],
                [$x, $y + 2, $z + 1],
                [$x, $y + 2, $z - 1],

                [$x + 2, $y + 1, $z + 2],
                [$x - 2, $y + 1, $z - 2],
                [$x + 2, $y + 1, $z],
                [$x + 2, $y, $z + 2],
                [$x - 2, $y, $z - 2],
                [$x + 2, $y, $z],

                [$x - 2, $y + 1, $z],
                [$x - 2, $y + 1, $z],
                [$x - 2, $y + 1, $z],
                [$x - 2, $y + 1, $z],
                [$x - 2, $y + 1, $z],
                [$x - 2, $y + 1, $z],

                [$x, $y + 1, $z + 2],
                [$x, $y + 1, $z + 2],
                [$x, $y + 1, $z + 2],
                [$x, $y + 1, $z + 2],
                [$x, $y + 1, $z + 2],
                [$x, $y + 1, $z + 2],

                [$x + 2, $y + 1, $z],
                [$x + 2, $y + 1, $z],
                [$x + 2, $y + 1, $z],
                [$x + 2, $y + 1, $z],
                [$x + 2, $y + 1, $z],
                [$x + 2, $y + 1, $z]
            ];

            foreach ($blocks as $block) {
                $pos = new Position($block[0], $block[1], $block[2], $world);
                $this->blocks[] = $this->getStringByPosition($pos);
                if ($pos->getWorld()->getBlock($pos)->getId() == 0) {
                    $player->getWorld()->setBlock($pos, BlockFactory::getInstance()->get(BlockLegacyIds::GLASS, 1), true);
                }
            }

            $this->getScheduler()->scheduleDelayedTask(new ClosureTask(function () use ($blocks, $world) {
                foreach ($blocks as $block) {
                    $pos = new Position($block[0], $block[1], $block[2], $world);
                    unset($this->blocks[array_search($pos, $this->blocks)]);
                    if ($pos->getWorld()->getBlock($pos) instanceof Glass) {
                        $pos->getWorld()->setBlock($pos, BlockFactory::getInstance()->get(BlockLegacyIds::AIR, 0), true);
                    }
                }
            }), 20 * $this->getConfig()->get("glass_time"));
        }
    }

    public function getStringByPosition(Position $position): string
    {
        return "{$position->x}!{$position->y}!{$position->z}";
    }
}