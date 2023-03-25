<?php

namespace Digueloulou12;

use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\plugin\PluginBase;
use pocketmine\event\Listener;
use pocketmine\block\BlockIds;
use pocketmine\level\Position;
use pocketmine\block\Block;
use pocketmine\Player;
use pocketmine\Server;

class MagicStick extends PluginBase implements Listener
{
    private static MagicStick $main;
    public static array $blocks = [];
    public static array $time = [];

    public function onEnable()
    {
        self::$main = $this;
        $this->saveDefaultConfig();
        $this->getServer()->getPluginManager()->registerEvents($this, $this);
    }

    public static function getInstance(): MagicStick
    {
        return self::$main;
    }

    public function onTap(EntityDamageByEntityEvent $event)
    {
        $player = $event->getDamager();
        $sender = $event->getEntity();

        if (!($sender instanceof Player) or !($player instanceof Player)) return;

        if (($player->getInventory()->getItemInHand()->getId() === $this->getConfig()->get("item")[0]) and ($player->getInventory()->getItemInHand()->getDamage() === $this->getConfig()->get("item")[1])) {
            if (!(isset(self::$time[$player->getName()])) or (self::$time[$player->getName()] < time())) {
                $x = $sender->getX();
                $y = $sender->getY();
                $z = $sender->getZ();
                $world = $sender->getLevel()->getId();

                $blocks = [
                    // [$x, $y, $z, $world], [$x, $y + 1, $z, $world],
                    [$x + 1, $y, $z, $world], [$x - 1, $y, $z, $world],
                    [$x + 1, $y + 1, $z, $world], [$x - 1, $y + 1, $z, $world],
                    [$x, $y, $z + 1, $world], [$x, $y, $z - 1, $world],
                    [$x, $y + 1, $z + 1, $world], [$x, $y + 1, $z - 1, $world],
                    [$x, $y + 2, $z, $world]


                    // [$x + 1, $y, $z + 1, $world], [$x + 1, $y + 1, $z + 1, $world],
                    // [$x - 1, $y, $z - 1, $world], [$x - 1, $y + 1, $z - 1, $world],
                    // [$x + 1, $y, $z - 1, $world], [$x + 1, $y + 1, $z - 1, $world],
                    // [$x - 1, $y, $z + 1, $world], [$x - 1, $y + 1, $z + 1, $world]
                ];
                self::$blocks[$sender->getName()] = $blocks;
                new MagicTask($sender->getName(), $this->getConfig()->get("time"));
                foreach ($blocks as $block) {
                    $pos = new Position($block[0], $block[1], $block[2], Server::getInstance()->getLevel($block[3]));
                    if ($pos->getLevel()->getBlock($pos)->getId() == 0) {
                        $sender->getLevel()->setBlock($pos, Block::get(BlockIds::COBWEB), false, true);
                    }
                }
                self::$time[$player->getName()] = time() + $this->getConfig()->get("cooldown");
            } else {
                $time = self::$time[$player->getName()] - time();
                $player->sendPopup(str_replace("{time}", $time, $this->getConfig()->get("popup")));
            }
        }
    }
}